@extends('all.component.app')

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #F6F6F6;
            background: linear-gradient(180deg, #287094, #D4D4CE, #F6F6F6, #023246);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            padding-bottom: 80px;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        h2 {
            color: #023246;
            font-weight: 700;
            margin-bottom: 2rem;
            font-size: 2.2rem;
        }

        .card {
            background-color: #FFFFFF;
            border: 1px solid #D4D4CE;
            border-radius: 15px;
            transition: transform 0.2s ease, box-shadow 0.3s ease;
            overflow: hidden;
            height: 100%;
            margin-bottom: 30px;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }

        .card-title {
            color: #023246;
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            line-height: 1.4;
        }

        .card-text {
            color: #555;
            margin-bottom: 2rem;
            line-height: 1.6;
            font-size: 1rem;
        }

        .card-body {
            padding: 2rem;
        }

        .btn-primary {
            background-color: #287094;
            border: none;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-size: 1rem;
        }

        .btn-primary:hover {
            background-color: #023246;
        }

        .btn-create {
            background-color: #287094;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
        }

        .btn-create:hover {
            background-color: #023246;
        }

        .empty-wishlist {
            text-align: center;
            padding: 100px 0;
            color: #555;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            margin-top: 3rem;
        }

        .empty-wishlist i {
            font-size: 5rem;
            color: #D4D4CE;
            margin-bottom: 30px;
            display: block;
        }

        .body-wrapper {
            padding: 2rem 0;
        }

        .container {
            padding-top: 3rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .row {
            margin-left: -25px;
            margin-right: -25px;
        }

        .col-md-4 {
            padding: 25px;
        }

        .d-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .badge {
            background-color: #287094;
            color: white;
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 1rem;
            margin-left: 15px;
        }

        .wishlist-header {
            margin-top: 100px;
            margin-bottom: 4rem;
            background-color: white;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }
    </style>
@endpush

@section('content')
<div class="body-wrapper">
    <div class="container mt-5 pt-5">
        <div class="d-flex justify-content-between align-items-center mb-4-header">
            <h2>📚 Forum Diskusi <span class="badge">{{ count($diskusis) }}</span></h2>
            @if(auth()->user()->role === 'siswa')
                <a href="{{ route('diskusi.create') }}" class="btn btn-primary">Buat Diskusi</a>
            @endif
        </div>

        <!-- Diskusi Grid -->
        <div class="row mt mt-5">
            @forelse ($diskusis as $diskusi)
                <div class="col-md-4 mb-4 course-card">
                    <div class="card shadow-sm h-100">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <h5 class="card-title">{{ $diskusi->title }}</h5>
                                <p class="text-muted mb-2">oleh {{ $diskusi->user->name }}</p>
                                <p class="card-text">{{ Str::limit($diskusi->question, 100) }}</p>
                            </div>
                            <a href="{{ route('diskusi.show', $diskusi->id) }}" class="btn btn-primary w-100">Lihat Diskusi</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 empty-wishlist">
                    <i class="fas fa-comments"></i>
                    <h3>Belum Ada Diskusi</h3>
                    <p>Diskusi akan muncul di sini setelah pengguna membuatnya.</p>
                    <a href="{{ route('diskusi.create') }}" class="btn btn-primary mt-4">Mulai Diskusi</a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
