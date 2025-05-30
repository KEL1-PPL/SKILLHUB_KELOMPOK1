@extends('all.component.app')

@push('style')
<style>
    .content-container {
        padding: 2rem;
    }

    @media (min-width: 992px) {
        main {
            margin-left: 260px;
        }
    }

    .table-container {
        overflow-x: auto;
    }

    @media (max-width: 576px) {
        .btn-add-package {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="position-relative content-container">
    <div class="row">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between align-items-center p-3 bg-transparent border-0">
                <div>
                    <h2 class="mb-1">Live Class Tersedia</h2>
                    <p class="text-muted mb-0">Bergabunglah dengan live class untuk belajar langsung dari mentor</p>
                </div>
                <div class="text-end">
                    <i class="bi bi-camera-video fs-1 text-primary"></i>
                </div>
            </div>
       
            <!-- Live Classes yang Sedang Berlangsung -->
            @if(isset($liveClasses) && $liveClasses->count() > 0)
            <div class="card mb-4">
                <div class="card-header text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-broadcast me-2"></i>
                        Sedang Live Sekarang
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($liveClasses as $liveClass)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card border-danger">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-title mb-0">{{ $liveClass->title }}</h6>
                                        <span class="badge bg-danger">LIVE</span>
                                    </div>
                                    <p class="card-text text-muted small mb-2">{{ Str::limit($liveClass->description, 80) }}</p>
                                    
                                    <div class="mb-2">
                                        <small class="text-muted">
                                            <i class="bi bi-calendar me-1"></i>
                                            {{ $liveClass->formatted_datetime }}
                                        </small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <small class="text-muted">
                                            <i class="bi bi-camera-video me-1"></i>
                                            {{ $liveClass->platform }}
                                        </small>
                                        <span class="mx-2">•</span>
                                        <small class="text-muted">
                                            <i class="bi bi-people me-1"></i>
                                            {{ $liveClass->participants_count }} peserta
                                        </small>
                                    </div>
                                    
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('live-class-student.join', $liveClass->id) }}" 
                                           class="btn btn-danger btn-sm">
                                            <i class="bi bi-play-circle me-1"></i>
                                            Bergabung Sekarang
                                        </a>
                                        <a href="{{ route('live-class-student.show', $liveClass->id) }}" 
                                           class="btn btn-outline-secondary btn-sm">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Live Classes Mendatang -->
            <div class="card">
                <div class="card-header text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-clock me-2"></i>
                        Live Class Mendatang
                    </h5>
                </div>
                <div class="card-body">
                    @if(isset($upcomingClasses) && $upcomingClasses->count() > 0)
                        <div class="row">
                            @foreach($upcomingClasses as $upcomingClass)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title mb-0">{{ $upcomingClass->title }}</h6>
                                            <span class="badge bg-primary">Mendatang</span>
                                        </div>
                                        <p class="card-text text-muted small mb-2">{{ Str::limit($upcomingClass->description, 80) }}</p>
                                        
                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class="bi bi-calendar me-1"></i>
                                                {{ $upcomingClass->formatted_datetime }}
                                            </small>
                                        </div>
                                        
                                        <div class="mb-2">
                                            <small class="text-primary fw-bold">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ $upcomingClass->time_until_start }}
                                            </small>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <small class="text-muted">
                                                <i class="bi bi-camera-video me-1"></i>
                                                {{ $upcomingClass->platform }}
                                            </small>
                                            <span class="mx-2">•</span>
                                            <small class="text-muted">
                                                <i class="bi bi-people me-1"></i>
                                                {{ $upcomingClass->participants_count }} terdaftar
                                            </small>
                                        </div>
                                        
                                        <div class="d-grid">
                                            <a href="{{ route('live-class-student.show', $upcomingClass->id) }}" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-info-circle me-1"></i>
                                                Lihat Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-camera-video-off text-muted" style="font-size: 4rem;"></i>
                            <h5 class="text-muted mt-3">Belum Ada Live Class Mendatang</h5>
                            <p class="text-muted">Saat ini belum ada live class yang dijadwalkan. Silakan cek kembali nanti.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div class="toast show" role="alert">
        <div class="toast-header bg-success text-white">
            <i class="bi bi-check-circle me-2"></i>
            <strong class="me-auto">Berhasil</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            {{ session('success') }}
        </div>
    </div>
</div>
@endif

@if(session('error'))
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div class="toast show" role="alert">
        <div class="toast-header bg-danger text-white">
            <i class="bi bi-exclamation-triangle me-2"></i>
            <strong class="me-auto">Error</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            {{ session('error') }}
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toasts = document.querySelectorAll('.toast');
    toasts.forEach(toast => {
        setTimeout(() => {
            const bsToast = new bootstrap.Toast(toast);
            bsToast.hide();
        }, 5000);
    });
    
    setInterval(() => {
        window.location.reload();
    }, 30000);
});
</script>
@endpush