@extends('layouts.app')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container py-5">
    <h2 class="fw-bold text-primary mb-4">Tambah Rating</h2>

    <form action="{{ route('ratings.store') }}" method="POST" class="p-4 shadow-sm rounded bg-white">
        @csrf
        
        <div class="mb-3">
            <label for="course_name" class="form-label">Nama Kursus</label>
            <input type="text" name="course_name" id="course_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="value" class="form-label">Rating (1-5)</label>
            <select name="value" id="value" class="form-select" required>
                <option value="" disabled selected>Pilih rating</option>
                @for ($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </div>

        <div class="mb-3">
            <label for="comment" class="form-label">Review</label>
            <textarea name="comment" id="comment" rows="4" class="form-control"></textarea>
        </div>

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
