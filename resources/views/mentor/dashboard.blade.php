@extends('layouts.mentor.app')

@section('content')
<div class="container">
    <h1>Dashboard Mentor</h1>
    
    <div class="row mt-4">
        <!-- Statistik Kursus -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Kursus</h5>
                    <h2 class="card-text">{{ $totalCourses ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <!-- Statistik Siswa -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Siswa</h5>
                    <h2 class="card-text">{{ $totalStudents ?? 0 }}</h2>
                </div>
            </div>
        </div>

        <!-- Pendapatan Bulan Ini -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Pendapatan Bulan Ini</h5>
                    <h2 class="card-text">Rp {{ number_format($monthlyIncome ?? 0, 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Aktivitas Terbaru -->
    <div class="card mt-4">
        <div class="card-header">
            <h5>Aktivitas Terbaru</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Aktivitas</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentActivities ?? [] as $activity)
                        <tr>
                            <td>{{ $activity->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $activity->description }}</td>
                            <td>{{ $activity->status }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">Belum ada aktivitas</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection