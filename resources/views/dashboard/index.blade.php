@extends('dashboard.app')

@push('styles')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 'auto',
                contentHeight: 200,
                aspectRatio: 2
            });
            calendar.render();
        });
    </script>

@endpush

@section('content')
    <div class="body-wrapper">
        <div class="container mt-5 pt-5">
            @if(auth()->user()->role == 'admin')
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col">
                                <div style="height: 100%" id="user-count"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div style="height: 100%" id="learning"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif(auth()->user()->role == 'siswa')

                <h2 class="mb-4">📚 Kursus yang Diikuti & Progres</h2>
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Kursus</th>
                            <th>Progres</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($enrollments as $item)
                            <tr>
                                <td>{{ $item->course->title }}</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar" role="progressbar"
                                            style="width: {{ $item->progress->percentage_completed }}%;"
                                            aria-valuenow="{{ $item->progress->percentage_completed }}" aria-valuemin="0"
                                            aria-valuemax="100">
                                            {{ $item->progress->percentage_completed }}%
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if ($item->progress->status === 'Selesai')
                                        <span class="badge bg-success">Selesai</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Belum Selesai</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <h2 class="mt-5 mb-4">🕘 Riwayat Penyelesaian Kursus</h2>
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Kursus</th>
                            <th>Tanggal Selesai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($completionHistory as $history)
                            <tr>
                                <td>{{ $history->course->title }}</td>
                                <td>{{ $history->completed_at->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <h2 class="mb-4">📊 Analitik Kemajuan Siswa</h2>
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Siswa</th>
                            <th>Kursus</th>
                            <th>Area Kesulitan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($analytics as $a)
                            <tr>
                                <td>{{ $a->student->name }}</td>
                                <td>{{ $a->course->title }}</td>
                                <td>{{ $a->area_of_struggle }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <h2 class="mt-5 mb-4">📈 Statistik Area Kesulitan</h2>
                <canvas id="struggleChart" width="400" height="200"></canvas>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    @if(auth()->user()->role == 'admin')
        <script>
            fetch('/api/register-user')
                .then(response => response.json())
                .then(data => {
                    Highcharts.chart('user-count', {
                        chart: {
                            type: 'line'
                        },
                        title: {
                            text: 'Pendaftaran Siswa dan Mentor setiap bulan ({{ now()->year }})'
                        },
                        xAxis: {
                            categories: data.months.map(m => new Date(2024, m - 1, 1).toLocaleString('id-ID', { month: 'long' })),
                            title: { text: 'Bulan' }
                        },
                        yAxis: {
                            title: { text: 'Jumlah Pendaftaran' },
                            allowDecimals: false
                        },
                        plotOptions: {
                            line: {
                                dataLabels: { enabled: true },
                                enableMouseTracking: true
                            }
                        },
                        series: [
                            { name: 'Siswa', data: data.siswa },
                            { name: 'Mentor', data: data.mentor }
                        ]
                    });
                })
                .catch(error => console.error("Gagal mengambil data:", error));


            Highcharts.chart('learning', {
                chart: {
                    type: 'pie'
                },
                title: {
                    text: 'Departmental Strength of a Company'
                },
                subtitle: {
                    text: 'Custom animation of pie series'
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        borderWidth: 2,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            distance: 20
                        }
                    }
                },
                series: [{
                    enableMouseTracking: false,
                    animation: {
                        duration: 2000
                    },
                    colorByPoint: true,
                    data: [{
                        name: 'Customer Support',
                        y: 21.3
                    }, {
                        name: 'Development',
                        y: 18.7
                    }, {
                        name: 'Sales',
                        y: 20.2
                    }, {
                        name: 'Marketing',
                        y: 14.2
                    }, {
                        name: 'Other',
                        y: 25.6
                    }]
                }]
            });
        </script>
    @endif
    @if (auth()->user()->role == 'siswa')
        <script>

            Highcharts.chart('progress-chart', {
                chart: {
                    type: 'line'
                },
                title: {
                    text: 'Progress Menguasai Web Developer - Tahun 2025'
                },
                xAxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']
                },
                yAxis: {
                    title: {
                        text: 'Persentase Penguasaan (%)'
                    },
                    max: 100
                },
                tooltip: {
                    valueSuffix: '%'
                },
                series: [{
                    name: 'Frontend',
                    data: [10, 20, 35, 45, 55, 65, 70, 75, 80, 85, 90, 95]
                }, {
                    name: 'Backend',
                    data: [5, 10, 15, 25, 35, 50, 60, 65, 70, 80, 85, 90]
                }, {
                    name: 'Fullstack',
                    data: [3, 8, 18, 30, 40, 55, 63, 70, 78, 85, 92, 98]
                }]
            });
        </script>
    @endif

    @if(auth()->user()->role == 'mentor')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('struggleChart');
            const chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($grouped->keys()) !!},
                    datasets: [{
                        label: 'Jumlah Siswa',
                        data: {!! json_encode($grouped->values()) !!},
                        backgroundColor: 'rgba(13, 110, 253, 0.7)'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Jumlah Siswa' }
                        },
                        x: {
                            title: { display: true, text: 'Area Kesulitan' }
                        }
                    }
                }
            });
        </script>
    @endif
    <script>




        document.addEventListener('DOMContentLoaded', function () {
            // Add animation to product cards on scroll
            const cards = document.querySelectorAll('.product-card');

            const observerOptions = {
                threshold: 0.2
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            cards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                observer.observe(card);
            });
        });
                        </>
@endpush
