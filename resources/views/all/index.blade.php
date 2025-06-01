@extends('all.component.app')

@push('styles')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
        .progress-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        .student-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            background: #f8f9fa;
        }

        .course-item {
            background: white;
            border-radius: 6px;
            padding: 0.75rem;
            margin-bottom: 0.5rem;
            border-left: 4px solid #007bff;
        }

        .progress-mini {
            height: 8px;
            border-radius: 4px;
        }
    </style>
@endpush

@section('content')
    <div class="body-wrapper">
        <div class="container mt-5 pt-5">
            @if (auth()->user()->role == 'admin')
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
                                            style="width: {{ $item->progress?->percentage_completed ?? 0 }}%;"
                                            aria-valuenow="{{ $item->progress?->percentage_completed ?? 0 }}"
                                            aria-valuemin="0" aria-valuemax="100">
                                            {{ $item->progress?->percentage_completed ?? 0 }}%
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if ($item->progress && $item->progress->status === 'Selesai')
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
                                <td>{{ $history->submitted_at->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                {{-- Mentor Dashboard --}}
                <h2 class="mb-4">📊 Tracking Kemajuan Siswa</h2>

                {{-- Progress per Siswa --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">📈 Progress Detail per Siswa</h5>
                    </div>
                    <div class="card-body">
                        @if ($studentProgresses->count() > 0)
                            @foreach ($studentProgresses->groupBy('student_name') as $studentName => $courses)
                                <div class="student-card">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0">
                                            <strong>{{ $studentName }}</strong>
                                            <small class="text-muted">({{ $courses->first()['student_email'] }})</small>
                                        </h6>
                                        <span class="badge bg-secondary">
                                            {{ $courses->count() }} kursus
                                        </span>
                                    </div>

                                    @foreach ($courses as $course)
                                        <div class="course-item">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="fw-bold">{{ $course['course_title'] }}</span>
                                                <div class="d-flex align-items-center gap-2">
                                                    @if ($course['status'] === 'Selesai')
                                                        <span class="badge bg-success progress-badge">✓ Selesai</span>
                                                    @else
                                                        <span
                                                            class="badge bg-warning text-dark progress-badge">{{ $course['progress_percentage'] }}%</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="progress progress-mini mb-2">
                                                <div class="progress-bar
                                        @if ($course['progress_percentage'] >= 100) bg-success
                                        @elseif($course['progress_percentage'] >= 70) bg-info
                                        @elseif($course['progress_percentage'] >= 40) bg-warning
                                        @else bg-danger @endif"
                                                    style="width: {{ $course['progress_percentage'] }}%">
                                                </div>
                                            </div>
                                            <small class="text-muted">
                                                Terakhir diakses:
                                                {{ \Carbon\Carbon::parse($course['last_accessed'])->diffForHumans() }}
                                            </small>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-users fa-2x mb-2"></i>
                                <p>Belum ada siswa yang terdaftar dalam kursus.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Tabel Progress (Alternative View) --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">📋 Tabel Progress Siswa</h5>
                    </div>
                    <div class="card-body">
                        @if ($studentProgresses->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nama Siswa</th>
                                            <th>Kursus</th>
                                            <th>Progress</th>
                                            <th>Status</th>
                                            <th>Terakhir Diakses</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($studentProgresses as $progress)
                                            <tr>
                                                <td>{{ $progress['student_name'] }}</td>
                                                <td>{{ $progress['course_title'] }}</td>
                                                <td>
                                                    <div class="progress" style="height: 20px;">
                                                        <div class="progress-bar
                                                @if ($progress['progress_percentage'] >= 100) bg-success
                                                @elseif($progress['progress_percentage'] >= 70) bg-info
                                                @elseif($progress['progress_percentage'] >= 40) bg-warning
                                                @else bg-danger @endif"
                                                            style="width: {{ $progress['progress_percentage'] }}%">
                                                            {{ $progress['progress_percentage'] }}%
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($progress['status'] === 'Selesai')
                                                        <span class="badge bg-success">✓ Selesai</span>
                                                    @else
                                                        <span class="badge bg-warning text-dark">Berlangsung</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <small>{{ \Carbon\Carbon::parse($progress['last_accessed'])->format('d M Y, H:i') }}</small>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-table fa-2x mb-2"></i>
                                <p>Tidak ada data progress yang tersedia.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Analytics - Updated to use generated analytics data --}}
                <h2 class="mb-4">🔍 Analitik Area Kesulitan</h2>
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h5>Statistik Kesulitan per Course</h5>
                                <small class="text-muted">Menampilkan jumlah siswa yang mengalami kesulitan di setiap
                                    course</small>
                            </div>
                            <div class="card-body">
                                <canvas id="struggleChart" width="400" height="200"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>Detail Area Kesulitan</h5>
                            </div>
                            <div class="card-body">
                                @if ($generatedAnalytics->count() > 0)
                                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                        <table class="table table-sm">
                                            <thead class="sticky-top bg-light">
                                                <tr>
                                                    <th>Nama Siswa</th>
                                                    <th>Kursus</th>
                                                    <th>Area Kesulitan</th>
                                                    <th>Progress</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($generatedAnalytics as $analytic)
                                                    <tr>
                                                        <td><small><strong>{{ $analytic['student_name'] }}</strong></small>
                                                        </td>
                                                        <td><small>{{ $analytic['course_title'] }}</small></td>
                                                        <td>
                                                            <small class="text-danger">
                                                                {{ $analytic['area_of_struggle'] }}
                                                            </small>
                                                            <br>
                                                            <small class="text-muted" style="font-size: 0.7rem;">
                                                                💡 {{ $analytic['suggested_action'] }}
                                                            </small>
                                                        </td>
                                                        <td>
                                                            <small
                                                                class="badge
                                                @if ($analytic['progress_percentage'] < 20) bg-danger
                                                @elseif($analytic['progress_percentage'] < 50) bg-warning
                                                @else bg-info @endif">
                                                                {{ $analytic['progress_percentage'] }}%
                                                            </small>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    {{-- Summary Card --}}
                                    <div class="mt-3 p-2 bg-light rounded">
                                        <small class="text-muted">
                                            <strong>Ringkasan:</strong><br>
                                            📊 Total siswa bermasalah: {{ $generatedAnalytics->count() }}<br>
                                            📚 Course bermasalah:
                                            {{ $generatedAnalytics->unique('course_title')->count() }}<br>
                                            👥 Siswa unik bermasalah:
                                            {{ $generatedAnalytics->unique('student_id')->count() }}
                                        </small>
                                    </div>
                                @else
                                    <div class="text-center text-muted py-4">
                                        <i class="fas fa-chart-line fa-2x mb-2"></i>
                                        <p>Tidak ada area kesulitan yang terdeteksi saat ini.</p>
                                        <small>Semua siswa menunjukkan progress yang baik!</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    @if (auth()->user()->role == 'admin')
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
                            categories: data.months.map(m => new Date(2024, m - 1, 1).toLocaleString('id-ID', {
                                month: 'long'
                            })),
                            title: {
                                text: 'Bulan'
                            }
                        },
                        yAxis: {
                            title: {
                                text: 'Jumlah Pendaftaran'
                            },
                            allowDecimals: false
                        },
                        plotOptions: {
                            line: {
                                dataLabels: {
                                    enabled: true
                                },
                                enableMouseTracking: true
                            }
                        },
                        series: [{
                                name: 'Siswa',
                                data: data.siswa
                            },
                            {
                                name: 'Mentor',
                                data: data.mentor
                            }
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

    @if (auth()->user()->role == 'mentor')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('struggleChart');
                const generatedAnalytics = @json($generatedAnalytics);
                console.log('Generated Analytics:', generatedAnalytics);
                const courseGroups = {};

                generatedAnalytics.forEach(analytic => {
                    const courseTitle = analytic.course_title;
                    if (courseGroups[courseTitle]) {
                        courseGroups[courseTitle]++;
                    } else {
                        courseGroups[courseTitle] = 1;
                    }
                });

                const labels = Object.keys(courseGroups);
                const data = Object.values(courseGroups);

                console.log('Chart Labels (Courses):', labels);
                console.log('Chart Data (Student Count):', data);

                if (labels.length === 0) {
                    const chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: ['Tidak ada data'],
                            datasets: [{
                                label: 'Jumlah Siswa yang Kesulitan',
                                data: [0],
                                backgroundColor: 'rgba(108, 117, 125, 0.3)',
                                borderColor: 'rgba(108, 117, 125, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    enabled: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 1,
                                    title: {
                                        display: true,
                                        text: 'Jumlah Siswa'
                                    }
                                },
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Semua siswa menunjukkan progress yang baik!'
                                    }
                                }
                            }
                        }
                    });
                } else {
                    const chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Jumlah Siswa yang Kesulitan',
                                data: data,
                                backgroundColor: [
                                    'rgba(220, 53, 69, 0.7)',
                                    'rgba(255, 193, 7, 0.7)',
                                    'rgba(13, 110, 253, 0.7)',
                                    'rgba(25, 135, 84, 0.7)',
                                    'rgba(108, 117, 125, 0.7)',
                                    'rgba(214, 51, 132, 0.7)',
                                    'rgba(13, 202, 240, 0.7)',
                                    'rgba(255, 99, 132, 0.7)',
                                    'rgba(54, 162, 235, 0.7)',
                                    'rgba(255, 206, 86, 0.7)',
                                ],
                                borderColor: [
                                    'rgba(220, 53, 69, 1)',
                                    'rgba(255, 193, 7, 1)',
                                    'rgba(13, 110, 253, 1)',
                                    'rgba(25, 135, 84, 1)',
                                    'rgba(108, 117, 125, 1)',
                                    'rgba(214, 51, 132, 1)',
                                    'rgba(13, 202, 240, 1)',
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top'
                                },
                                tooltip: {
                                    callbacks: {
                                        title: function(context) {
                                            return `Course: ${context[0].label}`;
                                        },
                                        label: function(context) {
                                            return `${context.parsed.y} siswa mengalami kesulitan`;
                                        },
                                        afterLabel: function(context) {
                                            const courseName = context.label;
                                            const courseAnalytics = generatedAnalytics.filter(a => a
                                                .course_title === courseName);
                                            const struggles = [...new Set(courseAnalytics.flatMap(a => a
                                                .area_of_struggle.split(', ')))];
                                            return `Area kesulitan: ${struggles.join(', ')}`;
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Jumlah Siswa yang Kesulitan'
                                    },
                                    ticks: {
                                        stepSize: 1
                                    }
                                },
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Nama Course'
                                    },
                                    ticks: {
                                        maxRotation: 45,
                                        minRotation: 45,
                                        callback: function(value, index, values) {
                                            const label = this.getLabelForValue(value);
                                            return label.length > 20 ? label.substring(0, 20) + '...' :
                                                label;
                                        }
                                    }
                                }
                            },
                            animation: {
                                duration: 1000,
                                easing: 'easeOutQuart'
                            }
                        }
                    });
                }
            });
        </script>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
    </script>
@endpush
