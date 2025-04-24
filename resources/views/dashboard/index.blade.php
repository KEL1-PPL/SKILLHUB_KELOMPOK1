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
