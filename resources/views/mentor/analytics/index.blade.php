@extends('layouts.mentor.app')

@section('content')
<div class="container">
    <h1>Analitik Kemajuan Siswa</h1>

    <!-- Filter Section -->
    <div class="card mt-4">
        <div class="card-body">
            <form method="GET" action="{{ route('mentor.analytics') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Pilih Kursus</label>
                    <select name="course_id" class="form-select">
                        <option value="">Semua Kursus</option>
                        @if(isset($courses) && count($courses) > 0)
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ $course->title }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Periode</label>
                    <select name="period" class="form-select">
                        <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="year" {{ request('period') == 'year' ? 'selected' : '' }}>Tahun Ini</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex flex-column justify-content-end">
                    <button type="submit" class="btn btn-primary mb-2">Terapkan Filter</button>
                    <a href="{{ route('mentor.analytics') }}" class="btn btn-secondary">Reset Filter</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Grafik Kemajuan -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Grafik Kemajuan Siswa</h5>
        </div>
        <div class="card-body">
            @if(isset($chartData) && isset($chartData->labels) && isset($chartData->data))
                <canvas id="progressChart" height="300"></canvas>
            @else
                <div class="alert alert-info">
                    Belum ada data untuk ditampilkan dalam grafik
                </div>
            @endif
        </div>
    </div>

    <!-- Tabel Detail Siswa -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="mb-0">Detail Kemajuan Per Siswa</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nama Siswa</th>
                            <th>Kursus</th>
                            <th>Progress</th>
                            <th>Nilai Rata-rata</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($studentProgress) && count($studentProgress) > 0)
                            @foreach($studentProgress as $progress)
                                <tr>
                                    <td>{{ $progress->student_name ?? 'N/A' }}</td>
                                    <td>{{ $progress->course_title ?? 'N/A' }}</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" 
                                                style="width: {{ $progress->progress_percentage ?? 0 }}%"
                                                aria-valuenow="{{ $progress->progress_percentage ?? 0 }}" 
                                                aria-valuemin="0" aria-valuemax="100">
                                                {{ $progress->progress_percentage ?? 0 }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ isset($progress->average_score) ? number_format($progress->average_score, 1) : 'N/A' }}</td>
                                    <td>
                                        @php
                                            $status = $progress->status ?? 'Pending';
                                            $badgeClass = $status === 'Completed' ? 'success' : 
                                                        ($status === 'In Progress' ? 'warning' : 'secondary');
                                        @endphp
                                        <span class="badge bg-{{ $badgeClass }}">
                                            {{ $status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center">Belum ada data kemajuan siswa</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartElement = document.getElementById('progressChart');
    if (chartElement) {
        try {
            const ctx = chartElement.getContext('2d');
            const labels = @json($chartData->labels ?? []);
            const data = @json($chartData->data ?? []);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Rata-rata Kemajuan Siswa (%)',
                        data: data,
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.1,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            title: {
                                display: true,
                                text: 'Persentase Kemajuan'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Periode'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Error initializing chart:', error);
            chartElement.parentElement.innerHTML = '<div class="alert alert-danger">Gagal memuat grafik</div>';
        }
    }
});
</script>
@endpush
