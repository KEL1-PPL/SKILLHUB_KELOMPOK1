@extends('all.component.app')

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
        @media (min-width: 992px) {
            main {
                margin-left: 260px;
            }
        }

        .table-responsive img {
            margin-right: 10px;
        }

        .btn-wishlist {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            background: linear-gradient(135deg, #ff6b6b 0%, #e74c3c 100%);
            color: white !important;
            border: none !important;
            border-radius: 12px !important;
            padding: 12px 24px !important;
            font-size: 1.1rem !important;
            font-weight: 600 !important;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.2);
            gap: 10px;
        }

        .btn-wishlist:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(231, 76, 60, 0.3);
            background: linear-gradient(135deg, #e74c3c 0%, #ff6b6b 100%);
        }

        .btn-wishlist.added {
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
            box-shadow: 0 4px 15px rgba(46, 204, 113, 0.2);
        }

        .btn-wishlist.added:hover {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            box-shadow: 0 6px 20px rgba(46, 204, 113, 0.3);
        }

        .btn-wishlist i {
            font-size: 1.4rem !important;
            transition: transform 0.3s ease;
        }

        .btn-wishlist:hover i {
            transform: scale(1.1);
        }

        .btn-wishlist span {
            font-size: 1.1rem;
            font-weight: 600;
        }

        .card {
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 1.5rem;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #2c3e50;
        }

        .card-text {
            color: #666;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .rating {
            margin-bottom: 1.5rem;
        }

        .rating .star {
            font-size: 1.2rem;
            color: #f1c40f;
            margin-right: 2px;
        }
    </style>
    
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

                <!-- Top 6 Kursus Populer -->
                <div class="mb-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 class="mb-0">🔥 Top 6 Kursus Populer</h2>
                        <form class="d-flex" id="search-popular-courses" onsubmit="return false;">
                            <input type="text" class="form-control me-2" id="searchPopularInput" placeholder="Cari kursus, kategori, atau instruktur...">
                        </form>
                    </div>
                    <div class="row" id="popularCoursesList">
                        @foreach($popularCourses as $course)
                            <div class="col-md-4 mb-4 course-card popular-course-item" data-title="{{ strtolower($course->title) }}">
                                <div class="card shadow-sm h-100">
                                    <img src="{{ $course->image_url ?? (method_exists($course, 'getImageUrlAttribute') ? $course->image_url : asset('images/default-course.png')) }}" alt="{{ $course->title }}" style="height: 200px; object-fit: cover; border-radius: 8px 8px 0 0;">
                                    <div class="card-body d-flex flex-column justify-content-between">
                                        <div>
                                            <h5 class="card-title">{{ $course->title }}</h5>
                                            <div class="rating mb-2">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <span class="star {{ $i <= ($course->rating ?? 0) ? 'filled' : '' }}">⭐</span>
                                                @endfor
                                            </div>
                                        </div>
                                        <div>
                                            <button type="button" class="btn-wishlist @if(auth()->user()->wishlistCourses->contains($course->id)) added @endif" data-course-id="{{ $course->id }}">
                                                <i class="bi {{ auth()->user()->wishlistCourses->contains($course->id) ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                                                <span>{{ auth()->user()->wishlistCourses->contains($course->id) ? 'Sudah di Wishlist' : 'Tambah ke Wishlist' }}</span>
                                            </button>
                                            <a href="{{ route('features.course.show', $course->slug) }}" class="btn btn-primary btn-sm mt-auto w-100">Lihat Detail</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

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
            // Search/filter popular courses
            const searchInput = document.getElementById('searchPopularInput');
            const courseCards = document.querySelectorAll('.popular-course-item');
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    const query = this.value.toLowerCase();
                    courseCards.forEach(card => {
                        const title = card.getAttribute('data-title');
                        card.style.display = title.includes(query) ? 'block' : 'none';
                    });
                });
            }
            // Wishlist button logic
            document.querySelectorAll('.btn-wishlist').forEach(function (button) {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const courseId = this.dataset.courseId;
                    const btn = this;
                    const isAdded = btn.classList.contains('added');
                    const url = '/wishlist';
                    const method = isAdded ? 'DELETE' : 'POST';
                    fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ course_id: courseId })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            btn.classList.toggle('added');
                            btn.innerHTML = btn.classList.contains('added')
                                ? '<i class="bi bi-heart-fill"></i> <span>Sudah di Wishlist</span>'
                                : '<i class="bi bi-heart"></i> <span>Tambah ke Wishlist</span>';
                        } else {
                            alert(data.message || 'Terjadi kesalahan');
                        }
                    })
                    .catch(() => alert('Terjadi kesalahan'));
                });
            });
        });
    </script>
@endpush
