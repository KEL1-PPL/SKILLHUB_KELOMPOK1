@extends('layouts.app')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="container py-5">
    <h2 class="fw-bold text-primary text-center mb-4">Tambah Rating</h2>

    <form action="{{ route('ratings.store') }}" method="POST" class="p-4 shadow-sm rounded bg-light">
        @csrf
        
        <!-- Dropdown Pilih Kursus -->
        <div class="mb-4">
            <label for="course_id" class="form-label fs-5">Pilih Kursus</label>
            <select name="course_id" id="course_id" class="form-select @error('course_id') is-invalid @enderror" required>
                <option value="" disabled selected>Pilih kursus</option>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}">{{ $course->title }}</option> 
                @endforeach
            </select>
            @error('course_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Dropdown Rating -->
        <div class="mb-4">
            <label for="value" class="form-label fs-5">Rating (1-5)</label>
            <select name="value" id="value" class="form-select @error('value') is-invalid @enderror" required>
                <option value="" disabled selected>Pilih rating</option>
                @for ($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
            @error('value')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Input Komentar -->
        <div class="mb-4">
            <label for="comment" class="form-label fs-5">Review</label>
            <textarea name="comment" id="comment" rows="4" class="form-control @error('comment') is-invalid @enderror"></textarea>
            @error('comment')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Tombol Simpan dan Kembali -->
        <div class="d-flex justify-content-between">
            <a href="{{ route('ratings.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Simpan Rating
            </button>
        </div>
    </form>
</div>

@endsection
