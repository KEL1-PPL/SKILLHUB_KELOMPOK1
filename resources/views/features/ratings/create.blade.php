@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4>Tambah Rating</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('ratings.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="course_id" class="form-label">Pilih Kursus</label>
                    <select name="course_id" id="course_id" class="form-select" required>
                        <option value="" disabled selected>Pilih kursus</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="value" class="form-label">Rating (1-5)</label>
                    <select name="value" id="value" class="form-select" required>
                        <option value="" disabled selected>Pilih rating</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="comment" class="form-label">Review</label>
                    <textarea name="comment" id="comment" rows="3" class="form-control" placeholder="Tulis review..." required></textarea>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="{{ route('ratings.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">Simpan Rating</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
