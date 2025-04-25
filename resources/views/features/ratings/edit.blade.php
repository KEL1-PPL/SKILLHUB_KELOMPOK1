@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold text-primary text-center mb-4">Edit Rating</h2>

    <!-- Form untuk Mengedit Rating -->
    <form action="{{ route('ratings.update', $rating->id) }}" method="POST" class="p-4 shadow-sm rounded bg-light">
        @csrf
        @method('PUT')

        <!-- Dropdown Pilih Kursus -->
        <div class="mb-4">
            <label for="course_id" class="form-label fs-5">Nama Kursus</label>
            <select class="form-select @error('course_id') is-invalid @enderror" id="course_id" name="course_id" required>
                <option value="" disabled selected>Pilih Kursus</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" 
                        {{ $course->id == old('course_id', $rating->course_id) ? 'selected' : '' }}>
                        {{ $course->title }}
                    </option>
                @endforeach
            </select>
            @error('course_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Dropdown Rating -->
        <div class="mb-4">
            <label for="rating" class="form-label fs-5">Rating (1-5)</label>
            <select class="form-select @error('value') is-invalid @enderror" id="rating" name="value" required>
                <option value="" disabled selected>Pilih Rating</option>
                @for ($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}" {{ old('value', $rating->value) == $i ? 'selected' : '' }}>
                        {{ $i }}
                    </option>
                @endfor
            </select>
            @error('value')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Input Review -->
        <div class="mb-4">
            <label for="review" class="form-label fs-5">Review</label>
            <textarea class="form-control @error('comment') is-invalid @enderror" id="review" name="comment" rows="4" required>{{ old('comment', $rating->comment) }}</textarea>
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
                <i class="bi bi-save"></i> Perbarui Rating
            </button>
        </div>
    </form>
</div>
@endsection
