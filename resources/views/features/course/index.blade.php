@extends('all.component.app')

@push('styles')
    <!-- Google Fonts: Figtree -->
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #F6F6F6;
            background: linear-gradient(180deg, #287094, #D4D4CE, #F6F6F6, #023246);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        h2 {
            color: #023246;
            font-weight: 700;
        }

        .search-bar {
            border: 2px solid #D4D4CE;
            border-radius: 8px;
        }

        .card {
            background-color: #FFFFFF;
            border: 1px solid #D4D4CE;
            border-radius: 10px;
            transition: transform 0.2s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        }

        .card-title {
            color: #023246;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .card-text {
            color: #555;
        }

        .rating .star {
            font-size: 16px;
            color: #f39c12;
        }

        .btn-primary {
            background-color: #287094;
            border: none;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #023246;
        }

        .wishlist-btn {
            background-color: #FFB6C1;
            border: none;
            font-weight: 600;
        }

        .wishlist-btn:hover {
            background-color: #FF69B4;
        }

        .course-card img {
            height: 200px;
            object-fit: cover;
            border-radius: 8px 8px 0 0;
        }
    </style>
@endpush

@section('content')
    <div class="body-wrapper">
        <div class="container mt-5 pt-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>📚 Jelajahi Kursus Populer</h2>
                @if(auth()->user() && (auth()->user()->role === 'admin' || auth()->user()->role === 'mentor'))
                    <a href="{{ route('features.course.create') }}" class="btn btn-primary">Tambah Kursus</a>
                @endif
            </div>

            <!-- Search Bar -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <input type="text" id="search-course" class="form-control search-bar" placeholder="🔍 Cari kursus...">
                </div>
            </div>

            <!-- Course Grid -->
            <div class="row" id="course-list">
                @foreach ($courses as $course)
                    <div class="col-md-4 mb-4 course-card" data-title="{{ strtolower($course->title) }}">
                        <div class="card shadow-sm h-100">
                            <!-- Gambar Kursus -->
                            <img src="{{ asset('storage/courses/' . $course->image) }}"
                                 class="card-img-top" alt="{{ $course->title }}">

                            <div class="card-body d-flex flex-column justify-content-between">
                                <div>
                                    <h5 class="card-title">{{ $course->title }}</h5>
                                    <p class="card-text">{{ $course->description }}</p>
                                </div>
                                <div>
                                    <div class="rating mb-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <span class="star {{ $i <= $course->rating ? 'filled' : '' }}">⭐</span>
                                        @endfor
                                    </div>
                                    <button class="wishlist-btn btn btn-sm mb-2 w-100">Tambah ke Wishlist</button>
                                    <a href="{{ route('features.course.show', $course->slug) }}" class="btn btn-primary btn-sm mt-auto w-100">Lihat Detail</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('search-course');
            const courseCards = document.querySelectorAll('.course-card');

            searchInput.addEventListener('input', function () {
                const query = this.value.toLowerCase();

                courseCards.forEach(card => {
                    const title = card.getAttribute('data-title');
                    card.style.display = title.includes(query) ? 'block' : 'none';
                });
            });
        });
    </script>
@endpush