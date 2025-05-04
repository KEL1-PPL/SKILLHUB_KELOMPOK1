@extends('dashboard.app')

@push('styles')
<style>
    .progress {
        height: 25px;
        border-radius: 12px;
    }
    .progress-bar {
        font-weight: bold;
    }
    .card {
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        font-weight: bold;
    }
    .status-badge {
        font-size: 14px;
        padding: 8px 12px;
        border-radius: 50px;
        width: 100px;
        display: inline-block;
        text-align: center;
    }
    .percentage-indicator {
        font-size: 18px;
        font-weight: bold;
    }
    .dashboard-stats {
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
    }
    .stat-item {
        text-align: center;
        padding: 15px;
        border-radius: 8px;
        background-color: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .stat-value {
        font-size: 24px;
        font-weight: bold;
    }
    .stat-label {
        color: #6c757d;
    }
    .course-title {
        font-weight: 600;
        color: #2c3e50;
    }
</style>
@endpush

@section('content')
<div class="body-wrapper">
    <div class="container mt-4 pt-3">
        <!-- Visual Indicators & Stats Dashboard (PBI #38) -->
        <div class="dashboard-stats row">
            <div class="col-md-3">
                <div class="stat-item">
                    <div class="stat-value text-primary">{{ count($enrollments) }}</div>
                    <div class="stat-label">Total Kursus</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-item">
                    <div class="stat-value text-success">{{ $enrollments->where('progress.status', 'Selesai')->count() }}</div>
                    <div class="stat-label">Kursus Selesai</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-item">
                    <div class="stat-value text-warning">{{ $enrollments->where('progress.status', '!=', 'Selesai')->count() }}</div>
                    <div class="stat-label">Kursus Berlangsung</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-item">
                    <div class="stat-value text-info">
                        @php
                            $totalPercentage = 0;
                            foreach($enrollments as $item) {
                                $totalPercentage += $item->progress->percentage_completed;
                            }
                            $averageProgress = count($enrollments) > 0 ? round($totalPercentage / count($enrollments)) : 0;
                            echo $averageProgress . '%';
                        @endphp
                    </div>
                    <div class="stat-label">Rata-rata Kemajuan</div>
                </div>
            </div>
        </div>

        <!-- Kursus yang Diikuti & Progres (PBI #37) -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">📚 Daftar Kursus & Kemajuan Belajar</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="35%">Nama Kursus</th>
                                <th width="35%">Kemajuan</th>
                                <th width="15%">Persentase</th>
                                <th width="15%">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($enrollments as $item)
                                <tr>
                                    <td>
                                        <span class="course-title">{{ $item->course->title }}</span>
                                    </td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar {{ $item->progress->percentage_completed == 100 ? 'bg-success' : 'bg-primary' }}" 
                                                role="progressbar"
                                                style="width: {{ $item->progress->percentage_completed }}%;"
                                                aria-valuenow="{{ $item->progress->percentage_completed }}" 
                                                aria-valuemin="0"
                                                aria-valuemax="100">
                                                {{ $item->progress->percentage_completed }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="percentage-indicator">{{ $item->progress->percentage_completed }}%</span>
                                    </td>
                                    <td class="text-center">
                                        @if ($item->progress->status === 'Selesai')
                                            <span class="status-badge bg-success text-white">Selesai</span>
                                        @else
                                            <span class="status-badge bg-warning text-dark">Belum Selesai</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Visual Breakdown of Progress -->
        <div class="card mt-4">
            <div class="card-header">
                <h4 class="mb-0">📊 Visualisasi Kemajuan Pembelajaran</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach ($enrollments as $item)
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $item->course->title }}</h5>
                                    <div class="progress mb-3">
                                        <div class="progress-bar {{ $item->progress->percentage_completed == 100 ? 'bg-success' : 'bg-primary' }}" 
                                            role="progressbar"
                                            style="width: {{ $item->progress->percentage_completed }}%;"
                                            aria-valuenow="{{ $item->progress->percentage_completed }}" 
                                            aria-valuemin="0"
                                            aria-valuemax="100">
                                            {{ $item->progress->percentage_completed }}%
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>
                                            <i class="fa fa-book"></i> Modul selesai: 
                                            <strong>{{ $item->progress->completed_modules }}/{{ $item->progress->total_modules }}</strong>
                                        </span>
                                        <span>
                                            @if ($item->progress->status === 'Selesai')
                                                <span class="badge bg-success">Selesai</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Belum Selesai</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Animasi untuk progress bar
        $('.progress-bar').each(function() {
            var width = $(this).attr('aria-valuenow');
            $(this).animate({
                width: width + '%'
            }, 1000);
        });
    });
</script>
@endpush