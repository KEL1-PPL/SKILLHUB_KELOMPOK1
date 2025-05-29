@extends('all.component.app')

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(180deg, #287094, #D4D4CE, #F6F6F6, #023246);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .card-article {
            background-color: #fff;
            border: 1px solid #D4D4CE;
            border-radius: 15px;
            box-shadow: 0 4px 24px rgba(40,112,148,0.08);
            overflow: hidden;
            margin-top: 2rem;
        }
        .card-article-header {
            background: linear-gradient(90deg, #287094 0%, #023246 100%);
            color: #fff;
            padding: 1.5rem 2rem;
            border-radius: 15px 15px 0 0;
        }
        .card-article-body {
            padding: 2rem;
        }
        .btn-primary {
            background-color: #287094;
            border: none;
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: #023246;
        }
        .form-label {
            font-weight: 600;
        }
    </style>
@endpush

@section('content')
<div class="body-wrapper">
    <div class="container mt-5 pt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card-article">
                    <div class="card-article-header">
                        <h3 class="mb-0">Buat Artikel Baru</h3>
                    </div>
                    <div class="card-article-body">
                        <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="title" class="form-label">Judul Artikel</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                                    name="title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="content" class="form-label">Konten</label>
                                <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="6" required>{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Gambar (Opsional)</label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Simpan Artikel</button>
                                <a href="{{ route('articles.index') }}" class="btn btn-secondary">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 