@extends('all.component.app')

@section('content')
    <div class="body-wrapper">
        <div class="container mt-5 pt-5">
            <div class="row">
                <div class="col-12 mb-3">
                    <a href="{{ route('features.course.show', $course->slug) }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left"></i> Kembali ke Detail Kursus
                    </a>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">Materi Kursus: {{ $course->title }}</h3>
                            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'mentor')
                                <a href="{{ route('features.material.create', $course->id) }}" class="btn btn-primary">
                                    <i class="ti ti-plus"></i> Tambah Materi
                                </a>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                @forelse($materials as $material)
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="mb-1">{{ $material->title }}</h5>
                                                <small class="text-muted">Urutan: {{ $material->order }}</small>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('features.material.show', [$course->id, $material->id]) }}" 
                                                   class="btn btn-sm btn-primary">
                                                    Lihat
                                                </a>
                                                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'mentor')
                                                    <a href="{{ route('features.material.edit', [$course->id, $material->id]) }}" 
                                                       class="btn btn-sm btn-warning">
                                                        Edit
                                                    </a>
                                                    <form action="{{ route('features.material.destroy', [$course->id, $material->id]) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                                onclick="return confirm('Apakah Anda yakin ingin menghapus materi ini?')">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="list-group-item">
                                        <p class="mb-0 text-center">Belum ada materi untuk kursus ini.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0">Progress Belajar</h4>
                        </div>
                        <div class="card-body">
                            @if(auth()->user()->role === 'siswa' && $progress !== null)
                                <div class="progress mb-3" style="height: 20px;">
                                    <div class="progress-bar" role="progressbar" 
                                         style="width: {{ $progress }}%;" 
                                         aria-valuenow="{{ $progress }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        {{ $progress }}%
                                    </div>
                                </div>
                                <p class="mb-0">Anda telah menyelesaikan {{ $progress }}% dari total materi.</p>
                            @else
                                <p class="mb-0">Progress belajar hanya tersedia untuk siswa.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 