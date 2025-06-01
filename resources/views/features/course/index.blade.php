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
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #2c3e50;
        }

        .card-text {
            color: #666;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .rating {
            margin-bottom: 1.5rem;
        }

        .rating .star {
            font-size: 1.2rem;
            color: #f1c40f;
            margin-right: 2px;
        }

        .btn-primary {
            background-color: #287094;
            border: none;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #023246;
        }

        .btn-wishlist {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            background: linear-gradient(135deg, #ff6b6b 0%, #e74c3c 100%);
            color: white !important;
            border: none !important;
            border-radius: 12px !important;
            padding: 12px 24px !important;
            font-size: 1.1rem !important;
            font-weight: 600 !important;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.2);
            gap: 10px;
        }

        .btn-wishlist:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(231, 76, 60, 0.3);
            background: linear-gradient(135deg, #e74c3c 0%, #ff6b6b 100%);
        }

        .btn-wishlist.added {
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
            box-shadow: 0 4px 15px rgba(46, 204, 113, 0.2);
        }

        .btn-wishlist.added:hover {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            box-shadow: 0 6px 20px rgba(46, 204, 113, 0.3);
        }

        .btn-wishlist i {
            font-size: 1.4rem !important;
            transition: transform 0.3s ease;
        }

        .btn-wishlist:hover i {
            transform: scale(1.1);
        }

        .btn-wishlist span {
            font-size: 1.1rem;
            font-weight: 600;
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
                @php $user = auth()->user(); @endphp
                @foreach ($courses as $course)
                    <div class="col-md-4 mb-4 course-card" data-title="{{ strtolower($course->title) }}">
                        <div class="card shadow-sm h-100">
                            <!-- Gambar Kursus -->
                            <img src="{{ asset('storage/' . $course->image) }}" alt="{{ $course->title }}">
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
                                    @if(auth()->check() && auth()->user()->role === 'siswa')
                                        <button type="button" class="btn-wishlist @if($user && $user->wishlistCourses->contains($course->id)) added @endif" data-course-id="{{ $course->id }}">
                                            <i class="bi {{ $user && $user->wishlistCourses->contains($course->id) ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                                            <span>{{ $user && $user->wishlistCourses->contains($course->id) ? 'Sudah di Wishlist' : 'Tambah ke Wishlist' }}</span>
                                        </button>
                                        @if($user->isEnrolledInCourse($course->id))
                                            <a href="{{ route('features.course.show', $course->slug) }}" class="btn btn-primary btn-sm mt-auto w-100">Lihat Detail</a>
                                        @else
                                            <form action="{{ route('course.enroll', $course->id) }}" method="POST" class="w-100">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm mt-auto w-100">Tambah ke Kursus Saya</button>
                                            </form>
                                        @endif
                                    @else
                                        <a href="{{ route('features.course.show', $course->slug) }}" class="btn btn-primary btn-sm mt-auto w-100">Lihat Detail</a>
                                    @endif
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

            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    const query = this.value.toLowerCase();
                    courseCards.forEach(card => {
                        const title = card.getAttribute('data-title');
                        card.style.display = title.includes(query) ? 'block' : 'none';
                    });
                });
            }

            // Wishlist button logic
            document.querySelectorAll('.btn-wishlist').forEach(function (button) {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const courseId = this.dataset.courseId;
                    const btn = this;
                    const isAdded = btn.classList.contains('added');
                    const url = '/wishlist';
                    const method = isAdded ? 'DELETE' : 'POST';
                    fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ course_id: courseId })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            btn.classList.toggle('added');
                            btn.innerHTML = btn.classList.contains('added')
                                ? '<i class="bi bi-heart-fill"></i> <span>Sudah di Wishlist</span>'
                                : '<i class="bi bi-heart"></i> <span>Tambah ke Wishlist</span>';
                        } else {
                            alert(data.message || 'Terjadi kesalahan');
                        }
                    })
                    .catch(() => alert('Terjadi kesalahan'));
                });
            });
        });
    </script>
@endpush
