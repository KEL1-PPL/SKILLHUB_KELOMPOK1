@extends('layouts.mentor.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manajemen Kursus</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCourseModal">
            <i class="bi bi-plus-circle"></i> Tambah Kursus
        </button>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Daftar Kursus -->
    <div class="row">
        @forelse($courses ?? [] as $course)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                @if($course->thumbnail)
                <img src="{{ asset('storage/' . $course->thumbnail) }}" class="card-img-top" alt="{{ $course->title }}">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $course->title }}</h5>
                    <p class="card-text">{{ Str::limit($course->description, 100) }}</p>
                    
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <span class="badge bg-{{ $course->status === 'active' ? 'success' : 'warning' }}">
                            {{ ucfirst($course->status) }}
                        </span>
                        <h6 class="mb-0">Rp {{ number_format($course->price, 0, ',', '.') }}</h6>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="bi bi-people"></i> {{ $course->students_count ?? 0 }} Siswa
                            <span class="mx-2">|</span>
                            <i class="bi bi-book"></i> {{ $course->lessons_count ?? 0 }} Pelajaran
                        </small>
                        <div class="btn-group">
                            <a href="{{ route('mentor.course.edit', $course->id) }}" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form id="delete-form-{{ $course->id }}" action="{{ route('mentor.course.destroy', $course->id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<button type="button" 
        class="btn btn-sm btn-outline-danger"
        onclick="event.preventDefault(); if(confirm('Apakah Anda yakin ingin menghapus kursus ini?')) document.getElementById('delete-form-{{ $course->id }}').submit();">
    <i class="bi bi-trash"></i>
</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                Belum ada kursus yang dibuat. Klik tombol "Tambah Kursus" untuk membuat kursus baru.
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- Modal Tambah Kursus -->
<div class="modal fade" id="createCourseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('mentor.course.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kursus Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Judul Kursus</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" required>
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="4" required></textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thumbnail</label>
                        <input type="file" name="thumbnail" class="form-control @error('thumbnail') is-invalid @enderror" 
                               accept="image/*">
                        @error('thumbnail')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga (Rp)</label>
                        <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" 
                               required min="0">
                        @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function deleteCourse(courseId) {
    if (confirm('Apakah Anda yakin ingin menghapus kursus ini?')) {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch(`/mentor/course/${courseId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': token,
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Gagal menghapus kursus. Silakan coba lagi.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan. Silakan coba lagi.');
        });
    }
}
</script>
@endpush
@endsection