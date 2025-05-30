@extends('all.component.app')

@section('content')
<div class="container-fluid" style="padding-top: 30px;">
    {{-- Judul halaman --}}
    <div class="row mb-3">
        <div class="col-lg-10 offset-lg-2 col-md-12 px-4">
            <h1 class="h4 fw-bold">Tambah Live Class Baru</h1>
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

                    <form action="{{ route('live-class.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul Live Class <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title') }}" 
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
                                      required>{{ old('description') }}</textarea>
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
                                           value="{{ old('datetime') }}" 
                                           min="{{ now()->format('Y-m-d\TH:i') }}"
                                           required>
                                    @error('datetime')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
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
                                        <option value="Zoom" {{ old('platform') == 'Zoom' ? 'selected' : '' }}>Zoom</option>
                                        <option value="Google Meet" {{ old('platform') == 'Google Meet' ? 'selected' : '' }}>Google Meet</option>
                                        <option value="Microsoft Teams" {{ old('platform') == 'Microsoft Teams' ? 'selected' : '' }}>Microsoft Teams</option>
                                        <option value="YouTube Live" {{ old('platform') == 'YouTube Live' ? 'selected' : '' }}>YouTube Live</option>
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
                                   value="{{ old('link') }}" 
                                   placeholder="https://example.com/live-class" 
                                   required>
                            @error('link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('live-class.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-2"></i>Simpan Live Class
                            </button>
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
    // Set minimum datetime to current time
    document.addEventListener('DOMContentLoaded', function() {
        const datetimeInput = document.getElementById('datetime');
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        
        const minDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
        datetimeInput.min = minDateTime;
    });
</script>
@endpush