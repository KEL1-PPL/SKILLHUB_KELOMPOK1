@extends('all.component.app')

@section('content')
    <div class="body-wrapper">
        <div class="container mt-5 pt-5">
            <div class="row">
                <div class="col-12 mb-3">
                    <a href="{{ route('features.course.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left"></i> Kembali ke Daftar Kursus
                    </a>
                    @if (auth()->user()->role === 'mentor' || auth()->user()->role === 'admin')
                        <a href="{{ route('features.course.edit', $course->slug) }}" class="btn btn-warning">
                            <i class="ti ti-pencil"></i> Edit Kursus
                        </a>
                        <form action="{{ route('features.course.destroy', $course->slug) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus kursus ini?')">
                                <i class="ti ti-trash"></i> Hapus Kursus
                            </button>
                        </form>
                    @endif
                </div>
                <div class="col-md-8">
                    <div class="card mb-4">
                    <img src="{{ $course->image_url }}" class="card-img-top" alt="{{ $course->title }}">
                        <div class="card-body">
                            <h1 class="card-title">{{ $course->title }}</h1>
                            <div class="rating mb-3">
                                @for ($i = 1; $i <= 5; $i++)
                                    <span class="star {{ $i <= $course->rating ? 'filled' : '' }}">⭐</span>
                                @endfor
                            </div>
                            <p class="card-text">{{ $course->description }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Informasi Kursus</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <strong>Dibuat oleh:</strong> {{ $course->creator->name }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Tanggal Dibuat:</strong> {{ $course->created_at->format('d M Y') }}
                                </li>
                            </ul>
                            <div class="mt-3">
                                <button class="wishlist-btn btn btn-sm w-100 mb-2">Tambah ke Wishlist</button>
                                <a href="{{ route('features.material.index', $course->id) }}" class="btn btn-primary w-100">Mulai Belajar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
