@extends('all.component.app')

@section('content')
<div class="container-fluid" style="padding-top: 30px;">
    {{-- Judul halaman --}}
    <div class="row mb-3">
        <div class="col-lg-10 offset-lg-2 col-md-12 px-4">
            <h1 class="h4 fw-bold">Edit Live Class</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10 offset-lg-2 col-md-12 px-4">
            <div class="card shadow">
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Status Alert -->
                    @if(!$liveClass->isUpcoming())
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Perhatian:</strong> Live class ini sudah dimulai atau selesai. Beberapa perubahan mungkin tidak akan berpengaruh.
                        </div>
                    @endif

                    <form action="{{ route('live-class.update', $liveClass->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul Live Class <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $liveClass->title) }}" 
                                   placeholder="Masukkan judul live class" 
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      placeholder="Masukkan deskripsi live class" 
                                      required>{{ old('description', $liveClass->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="datetime" class="form-label">Tanggal & Waktu <span class="text-danger">*</span></label>
                                    <input type="datetime-local" 
                                           class="form-control @error('datetime') is-invalid @enderror" 
                                           id="datetime" 
                                           name="datetime" 
                                           value="{{ old('datetime', $liveClass->datetime->format('Y-m-d\TH:i')) }}" 
                                           required>
                                    @error('datetime')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if($liveClass->isUpcoming())
                                        <div class="form-text">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Live class saat ini dijadwalkan {{ $liveClass->time_until_start }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="platform" class="form-label">Platform <span class="text-danger">*</span></label>
                                    <select class="form-select @error('platform') is-invalid @enderror" 
                                            id="platform" 
                                            name="platform" 
                                            required>
                                        <option value="">Pilih Platform</option>
                                        <option value="Zoom" {{ old('platform', $liveClass->platform) == 'Zoom' ? 'selected' : '' }}>Zoom</option>
                                        <option value="Google Meet" {{ old('platform', $liveClass->platform) == 'Google Meet' ? 'selected' : '' }}>Google Meet</option>
                                        <option value="Microsoft Teams" {{ old('platform', $liveClass->platform) == 'Microsoft Teams' ? 'selected' : '' }}>Microsoft Teams</option>
                                        <option value="YouTube Live" {{ old('platform', $liveClass->platform) == 'YouTube Live' ? 'selected' : '' }}>YouTube Live</option>
                                    </select>
                                    @error('platform')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="link" class="form-label">Link Akses <span class="text-danger">*</span></label>
                            <input type="url" 
                                   class="form-control @error('link') is-invalid @enderror" 
                                   id="link" 
                                   name="link" 
                                   value="{{ old('link', $liveClass->link) }}" 
                                   placeholder="https://example.com/live-class" 
                                   required>
                            @error('link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="bi bi-link-45deg me-1"></i>
                                Link saat ini: <a href="{{ $liveClass->link }}" target="_blank">{{ Str::limit($liveClass->link, 50) }}</a>
                            </div>
                        </div>

                        <!-- Current Status Display -->
                        <div class="mb-4">
                            <label class="form-label">Status Saat Ini:</label>
                            <div>
                                @if($liveClass->LiveStatus == 'live')
                                    <span class="badge bg-danger fs-6">🔴 SEDANG BERLANGSUNG</span>
                                @elseif($liveClass->LiveStatus == 'upcoming')
                                    <span class="badge bg-warning fs-6">⏳ AKAN DATANG</span>
                                @else
                                    <span class="badge bg-secondary fs-6">✅ SELESAI</span>
                                @endif
                            </div>
                        </div>

                        <!-- Information Card -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="bi bi-info-circle me-2"></i>Informasi Tambahan
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="text-muted">Dibuat pada:</small><br>
                                        <span>{{ $liveClass->created_at->format('d M Y, H:i') }}</span>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted">Terakhir diperbarui:</small><br>
                                        <span>{{ $liveClass->updated_at->format('d M Y, H:i') }}</span>
                                    </div>
                                </div>
                                @if($liveClass->user)
                                    <div class="mt-2">
                                        <small class="text-muted">Dibuat oleh:</small><br>
                                        <span>{{ $liveClass->user->name }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('live-class.show', $liveClass->id) }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Kembali ke Detail
                            </a>
                            <div>
                                <a href="{{ route('live-class.index') }}" class="btn btn-outline-secondary me-2">
                                    <i class="bi bi-list me-2"></i>Daftar Live Class
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-2"></i>Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const datetimeInput = document.getElementById('datetime');
        const isUpcoming = {{ $liveClass->isUpcoming() ? 'true' : 'false' }};
        
        if (isUpcoming) {
            const now = new Date();
            const year = now.getFullYear();
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            
            const minDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
            datetimeInput.min = minDateTime;
        }

        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const datetime = new Date(datetimeInput.value);
            const now = new Date();
            
            if (isUpcoming && datetime <= now) {
                e.preventDefault();
                alert('Tanggal dan waktu harus di masa depan!');
                datetimeInput.focus();
                return false;
            }
        });
    });

    document.getElementById('platform').addEventListener('change', function() {
        const linkInput = document.getElementById('link');
        const platform = this.value;
        
        switch(platform) {
            case 'Zoom':
                linkInput.placeholder = 'https://zoom.us/j/123456789';
                break;
            case 'Google Meet':
                linkInput.placeholder = 'https://meet.google.com/abc-defg-hij';
                break;
            case 'Microsoft Teams':
                linkInput.placeholder = 'https://teams.microsoft.com/l/meetup-join/...';
                break;
            case 'YouTube Live':
                linkInput.placeholder = 'https://youtube.com/watch?v=...';
                break;
            case 'Facebook Live':
                linkInput.placeholder = 'https://facebook.com/...';
                break;
            case 'Instagram Live':
                linkInput.placeholder = 'https://instagram.com/...';
                break;
            default:
                linkInput.placeholder = 'https://example.com/live-class';
        }
    });
</script>
@endpush