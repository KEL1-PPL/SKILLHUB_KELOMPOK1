@extends('all.component.app')

@section('content')
<div class="container-fluid" style="padding-top: 30px;">
    {{-- Judul halaman --}}
    <div class="row mb-3">
        <div class="col-lg-10 offset-lg-2 col-md-12 px-4">
            <h1 class="h4 fw-bold">Detail Live Class</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10 offset-lg-2 col-md-12 px-4">
            <div class="mb-3">
                <a href="{{ route('live-class-student.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>

            <!-- Live Class Card -->
            <div class="card shadow">
                <div class="card-header {{ $liveClass->isLive() ? 'bg-danger text-white' : 'bg-primary text-white' }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">{{ $liveClass->title }}</h4>
                        @if($liveClass->isLive())
                            <span class="badge bg-light text-danger live-indicator">
                                <i class="bi bi-broadcast me-1"></i>LIVE
                            </span>
                        @elseif($liveClass->isUpcoming())
                            <span class="badge bg-light text-primary">
                                <i class="bi bi-clock me-1"></i>Mendatang
                            </span>
                        @else
                            <span class="badge bg-light text-secondary">
                                <i class="bi bi-check-circle me-1"></i>Selesai
                            </span>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <!-- Description -->
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Deskripsi</h6>
                        <p class="mb-0">{{ $liveClass->description }}</p>
                    </div>

                    <!-- Details -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-2">
                                <i class="bi bi-calendar me-2"></i>Jadwal
                            </h6>
                            <p class="mb-0">{{ $liveClass->formatted_datetime }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-2">
                                <i class="bi bi-camera-video me-2"></i>Platform
                            </h6>
                            <p class="mb-0">{{ $liveClass->platform }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-2">
                                <i class="bi bi-people me-2"></i>Peserta
                            </h6>
                            <p class="mb-0">{{ $liveClass->participants_count }} orang</p>
                        </div>

                        @if($liveClass->isUpcoming())
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-2">
                                <i class="bi bi-clock me-2"></i>Waktu Mulai
                            </h6>
                            <p class="mb-0 text-primary fw-bold">{{ $liveClass->time_until_start }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2">
                        @if($liveClass->isLive())
                            <a href="{{ route('live-class-student.join', $liveClass->id) }}" 
                               class="btn btn-danger btn-lg">
                                <i class="bi bi-play-circle me-2"></i>
                                Bergabung Sekarang
                            </a>
                        @elseif($liveClass->isUpcoming())
                            <button class="btn btn-secondary btn-lg" disabled>
                                <i class="bi bi-clock me-2"></i>
                                Live Class Belum Dimulai
                            </button>
                        @else
                            <button class="btn btn-outline-secondary btn-lg" disabled>
                                <i class="bi bi-check-circle me-2"></i>
                                Live Class Sudah Selesai
                            </button>
                        @endif
                    </div>
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