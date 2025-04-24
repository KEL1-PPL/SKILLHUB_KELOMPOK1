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
    <style>
        /* Gaya untuk progress bar */
        .progress {
            height: 25px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
            margin-bottom: 0;
        }
        
        .progress-bar {
            font-weight: bold;
            text-shadow: 1px 1px 1px rgba(0,0,0,0.2);
            transition: width 1s ease;
            font-size: 14px;
            line-height: 25px;
        }
        
        /* Gaya untuk status badge */
        .status-badge {
            padding: 8px 14px;
            border-radius: 50px;
            font-weight: 600;
            min-width: 120px;
            display: inline-block;
            text-align: center;
        }
        
        /* Kartu ringkasan */
        .summary-card {
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            padding: 15px;
            text-align: center;
            margin-bottom: 20px;
            border: none;
            transition: transform 0.3s ease;
        }
        
        .summary-card:hover {
            transform: translateY(-5px);
        }
        
        .summary-value {
            font-size: 28px;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .summary-label {
            color: #6c757d;
            font-size: 16px;
        }
        
        /* Circle percentage indicator */
        .percentage-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 16px;
            margin: 0 auto;
        }
        
        /* Course card with visual elements */
        .course-card {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            border: none;
        }
        
        .course-card-header {
            padding: 15px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #eee;
        }
        
        .course-card-body {
            padding: 15px;
        }
        
        .course-status-indicator {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }
        
        .course-status-text {
            margin-left: 10px;
            font-weight: 600;
        }
        
        /* Dashboard header */
        .dashboard-header {
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e9ecef;
        }

        /* Animation for progress bars */
        @keyframes progressAnimation {
            0% { width: 0; }
        }
        
        .animate-progress {
            animation: progressAnimation 1.5s ease-out forwards;
        }
    </style>
@endpush

@section('content')
    <div class="body-wrapper">
        <div class="container mt-5 pt-5">
            @if(auth()->user()->role == 'siswa')
                <div class="dashboard-header">
                    <h2>📊 Dashboard Kemajuan Pembelajaran</h2>
                    <p class="text-muted">Pantau kemajuan kursus Anda dengan indikator visual</p>
                </div>
                
                <!-- Visual Summary Indicators -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="summary-card bg-white">
                            <i class="fas fa-book fa-2x text-primary"></i>
                            <div class="summary-value">{{ count($enrollments) }}</div>
                            <div class="summary-label">Total Kursus</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="summary-card bg-white">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                            <div class="summary-value">{{ $enrollments->where('progress.status', 'Selesai')->count() }}</div>
                            <div class="summary-label">Kursus Selesai</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="summary-card bg-white">
                            <i class="fas fa-spinner fa-2x text-warning"></i>
                            <div class="summary-value">{{ $enrollments->where('progress.status', '!=', 'Selesai')->count() }}</div>
                            <div class="summary-label">Sedang Berlangsung</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="summary-card bg-white">
                            @php
                                $totalPercentage = 0;
                                foreach($enrollments as $item) {
                                    $totalPercentage += $item->progress->percentage_completed;
                                }
                                $averageProgress = count($enrollments) > 0 ? round($totalPercentage / count($enrollments)) : 0;
                                
                                // Menentukan warna berdasarkan persentase
                                if ($averageProgress >= 75) {
                                    $avgColor = '#28a745'; // hijau
                                } elseif ($averageProgress >= 50) {
                                    $avgColor = '#17a2b8'; // biru
                                } elseif ($averageProgress >= 25) {
                                    $avgColor = '#ffc107'; // kuning
                                } else {
                                    $avgColor = '#dc3545'; // merah
                                }
                            @endphp
                            <div class="percentage-circle" style="background-color: {{ $avgColor }}">
                                {{ $averageProgress }}%
                            </div>
                            <div class="summary-value">{{ $averageProgress }}%</div>
                            <div class="summary-label">Rata-rata Kemajuan</div>
                        </div>
                    </div>
                </div>

                <!-- Kursus yang Diikuti dengan Indikator Visual -->
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">📚 Kursus yang Diikuti & Progres</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="30%">Kursus</th>
                                        <th width="40%">Progres</th>
                                        <th width="15%">Persentase</th>
                                        <th width="15%">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($enrollments as $item)
                                        <tr>
                                            <td class="align-middle"><strong>{{ $item->course->title }}</strong></td>
                                            <td class="align-middle">
                                                <div class="progress">
                                                    <div class="progress-bar animate-progress {{ $item->progress->percentage_completed >= 100 ? 'bg-success' : 'bg-primary' }}" role="progressbar"
                                                        style="width: {{ $item->progress->percentage_completed }}%;"
                                                        aria-valuenow="{{ $item->progress->percentage_completed }}" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                        {{ $item->progress->percentage_completed }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">
                                                @php
                                                    // Menentukan warna berdasarkan persentase
                                                    if ($item->progress->percentage_completed >= 75) {
                                                        $circleColor = '#28a745'; // hijau
                                                    } elseif ($item->progress->percentage_completed >= 50) {
                                                        $circleColor = '#17a2b8'; // biru
                                                    } elseif ($item->progress->percentage_completed >= 25) {
                                                        $circleColor = '#ffc107'; // kuning
                                                    } else {
                                                        $circleColor = '#dc3545'; // merah
                                                    }
                                                @endphp
                                                <div class="percentage-circle" style="background-color: {{ $circleColor }}">
                                                    {{ $item->progress->percentage_completed }}%
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">
                                                @if ($item->progress->status === 'Selesai')
                                                    <span class="status-badge bg-success text-white">
                                                        <i class="fas fa-check-circle me-1"></i> Selesai
                                                    </span>
                                                @else
                                                    <span class="status-badge bg-warning text-dark">
                                                        <i class="fas fa-clock me-1"></i> Belum Selesai
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Visual Course Cards -->
                <h4 class="mb-3">Visualisasi Detail Kursus</h4>
                <div class="row">
                    @foreach ($enrollments as $item)
                        <div class="col-md-6 mb-4">
                            <div class="course-card">
                                <div class="course-card-header">
                                    <h5 class="mb-0">{{ $item->course->title }}</h5>
                                </div>
                                <div class="course-card-body">
                                    <div class="progress mb-3">
                                        <div class="progress-bar animate-progress {{ $item->progress->percentage_completed >= 100 ? 'bg-success' : 'bg-primary' }}" role="progressbar"
                                            style="width: {{ $item->progress->percentage_completed }}%;"
                                            aria-valuenow="{{ $item->progress->percentage_completed }}" aria-valuemin="0"
                                            aria-valuemax="100">
                                            {{ $item->progress->percentage_completed }}%
                                        </div>
                                    </div>
                                    
                                    <div class="course-status-indicator">
                                        @if ($item->progress->status === 'Selesai')
                                            <i class="fas fa-check-circle fa-2x text-success"></i>
                                            <span class="course-status-text text-success">Kursus Selesai</span>
                                        @else
                                            <i class="fas fa-spinner fa-2x text-warning"></i>
                                            <span class="course-status-text text-warning">Sedang Berlangsung</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

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
                
                <!-- Progress Chart -->
                <div class="card mt-4">
                    <div class="card-header bg-white">
                        <h4 class="mb-0">📈 Grafik Kemajuan Pembelajaran</h4>
                    </div>
                    <div class="card-body">
                        <div id="progress-chart" style="min-height: 300px;"></div>
                    </div>
                </div>
            @elseif(auth()->user()->role == 'admin')
                <!-- Admin content retained from original -->
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
            @else
                <!-- Mentor content retained from original -->
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
            // Progress Chart untuk siswa
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
            
            // Animasi progress bar
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(function() {
                    const progressBars = document.querySelectorAll('.progress-bar');
                    progressBars.forEach(bar => {
                        const width = bar.getAttribute('aria-valuenow') + '%';
                        bar.style.width = width;
                    });
                }, 300);
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
            const cards = document.querySelectorAll('.summary-card, .course-card');

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
    </script>
@endpush