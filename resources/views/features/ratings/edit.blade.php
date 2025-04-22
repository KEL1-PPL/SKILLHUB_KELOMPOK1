@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Edit Rating</h2>

    <!-- Form untuk Mengedit Rating -->
    <form action="{{ route('ratings.update', $rating->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="course_name">Nama Kursus</label>
            <input type="text" class="form-control @error('course_name') is-invalid @enderror" id="course_name" name="course_name" value="{{ old('course_name', $rating->course_name) }}" required>
            @error('course_name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="rating">Rating</label>
            <input type="number" class="form-control @error('rating') is-invalid @enderror" id="rating" name="rating" min="1" max="5" value="{{ old('rating', $rating->rating) }}" required>
            @error('rating')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label for="review">Review</label>
            <textarea class="form-control @error('review') is-invalid @enderror" id="review" name="review" rows="3" required>{{ old('review', $rating->review) }}</textarea>
            @error('review')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Perbarui Rating</button>
    </form>
</div>
@endsection
