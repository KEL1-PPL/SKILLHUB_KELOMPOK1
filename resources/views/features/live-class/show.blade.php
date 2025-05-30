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
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <a href="{{ route('live-class.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Status Badge -->
                            <div class="mb-4">
                                @if($liveClass->LiveStatus == 'live')
                                    <span class="badge bg-danger fs-6">🔴 SEDANG BERLANGSUNG</span>
                                @elseif($liveClass->LiveStatus == 'upcoming')
                                    <span class="badge bg-warning fs-6">⏳ AKAN DATANG</span>
                                @else
                                    <span class="badge bg-secondary fs-6">✅ SELESAI</span>
                                @endif
                            </div>

                            <!-- Title -->
                            <h2 class="mb-3">{{ $liveClass->title }}</h2>

                            <!-- Description -->
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">Deskripsi:</h6>
                                <p class="mb-0">{{ $liveClass->description }}</p>
                            </div>

                            <!-- Details -->
                            <div class="row mb-4">
                                <div class="col-sm-6">
                                    <h6 class="text-muted mb-2">
                                        <i class="bi bi-calendar-event me-2"></i>Tanggal & Waktu:
                                    </h6>
                                    <p class="mb-3">{{ $liveClass->formatted_datetime }}</p>
                                    
                                    @if($liveClass->isUpcoming())
                                        <p class="text-success mb-0">
                                            <small><strong>{{ $liveClass->time_until_start }}</strong></small>
                                        </p>
                                    @endif
                                </div>
                                <div class="col-sm-6">
                                    <h6 class="text-muted mb-2">
                                        <i class="bi bi-laptop me-2"></i>Platform:
                                    </h6>
                                    <p class="mb-0">
                                        <span class="badge bg-info">{{ $liveClass->platform }}</span>
                                    </p>
                                </div>
                            </div>

                            <!-- Access Link -->
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">
                                    <i class="bi bi-link-45deg me-2"></i>Link Akses:
                                </h6>
                                <div class="input-group">
                                    <input type="text" class="form-control" value="{{ $liveClass->link }}" readonly>
                                    <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('{{ $liveClass->link }}')">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                    <a href="{{ $liveClass->link }}" target="_blank" class="btn btn-primary" 
                                       @if(!$liveClass->isLive() && !$liveClass->isUpcoming()) disabled @endif>
                                        <i class="bi bi-box-arrow-up-right me-2"></i>Buka Link
                                    </a>
                                </div>
                            </div>

                            <!-- Creator Info -->
                            @if($liveClass->user)
                                <div class="mb-4">
                                    <h6 class="text-muted mb-2">
                                        <i class="bi bi-person me-2"></i>Dibuat oleh:
                                    </h6>
                                    <p class="mb-0">{{ $liveClass->user->name }}</p>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-4">
                            <!-- Quick Info Card -->
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Informasi Cepat</h6>
                                    
                                    <div class="mb-3">
                                        <small class="text-muted">Status:</small><br>
                                        @if($liveClass->status == 'live')
                                            <span class="text-danger fw-bold">Sedang Berlangsung</span>
                                        @elseif($liveClass->status == 'upcoming')
                                            <span class="text-warning fw-bold">Akan Datang</span>
                                        @else
                                            <span class="text-secondary fw-bold">Selesai</span>
                                        @endif
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted">Durasi Estimasi:</small><br>
                                        <span>2 Jam</span>
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted">Partisipan:</small><br>
                                        <span>{{ $liveClass->participants_count ?? 0 }} orang</span>
                                    </div>

                                    <div class="mb-0">
                                        <small class="text-muted">Dibuat:</small><br>
                                        <span>{{ $liveClass->created_at->format('d M Y, H:i') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            @if($liveClass->isUpcoming())
                                <div class="mt-3">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('live-class.edit', $liveClass->id) }}" class="btn btn-warning">
                                            <i class="bi bi-pencil me-2"></i>Edit Live Class
                                        </a>
                                        <form action="{{ route('live-class.destroy', $liveClass->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger w-100" 
                                                    onclick="return confirm('Yakin ingin menghapus live class ini?')">
                                                <i class="bi bi-trash me-2"></i>Hapus Live Class
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            const originalBtn = event.target.closest('button');
            const originalHTML = originalBtn.innerHTML;
            originalBtn.innerHTML = '<i class="bi bi-check"></i>';
            originalBtn.classList.remove('btn-outline-secondary');
            originalBtn.classList.add('btn-success');
            
            setTimeout(function() {
                originalBtn.innerHTML = originalHTML;
                originalBtn.classList.remove('btn-success');
                originalBtn.classList.add('btn-outline-secondary');
            }, 2000);
        }).catch(function(err) {
            console.error('Error copying text: ', err);
        });
    }
</script>
@endpush