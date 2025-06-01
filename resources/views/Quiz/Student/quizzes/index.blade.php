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
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        h2 {
            color: #023246;
            font-weight: 700;
        }

        .search-bar {
            border: 2px solid #D4D4CE;
            border-radius: 8px;
            padding: 0.75rem;
        }

        .search-bar:focus {
            border-color: #287094;
            box-shadow: 0 0 0 0.2rem rgba(40, 112, 148, 0.25);
        }

        .card {
            background-color: #FFFFFF;
            border: 1px solid #D4D4CE;
            border-radius: 10px;
            transition: transform 0.2s ease, box-shadow 0.3s ease;
            height: 100%;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            color: #023246;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            line-height: 1.3;
        }

        .card-text {
            color: #555;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .btn-primary {
            background-color: #287094;
            border: none;
            font-weight: 600;
            padding: 0.5rem 1rem;
        }

        .btn-primary:hover {
            background-color: #023246;
        }

        .btn-success {
            background-color: #28a745;
            border: none;
            font-weight: 600;
            padding: 0.5rem 1rem;
        }

        .btn-info {
            background-color: #17a2b8;
            border: none;
            font-weight: 600;
            padding: 0.5rem 1rem;
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
            font-weight: 600;
            padding: 0.5rem 1rem;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.4rem 0.6rem;
            border-radius: 15px;
        }

        .quiz-stats {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .quiz-card {
            margin-bottom: 1.5rem;
        }

        .quiz-difficulty {
            display: inline-block;
            padding: 0.3rem 0.6rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .difficulty-easy {
            background-color: #d4edda;
            color: #155724;
        }

        .difficulty-medium {
            background-color: #fff3cd;
            color: #856404;
        }

        .difficulty-hard {
            background-color: #f8d7da;
            color: #721c24;
        }

        .stats-item {
            text-align: center;
            padding: 0.5rem;
        }

        .stats-value {
            font-weight: 700;
            font-size: 1.1rem;
            display: block;
        }

        .stats-label {
            font-size: 0.75rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .quiz-info {
            font-size: 0.85rem;
            line-height: 1.6;
        }

        .quiz-info strong {
            color: #023246;
        }

        .score-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .empty-state {
            padding: 3rem 1rem;
            text-align: center;
        }

        .empty-state h5 {
            color: #6c757d;
            margin-bottom: 1rem;
        }

        .empty-state p {
            color: #9ca3af;
            margin-bottom: 1.5rem;
        }

        .alert {
            border-radius: 8px;
            border: none;
        }

        .pagination {
            justify-content: center;
        }

        .pagination .page-link {
            color: #287094;
            border-color: #D4D4CE;
        }

        .pagination .page-item.active .page-link {
            background-color: #287094;
            border-color: #287094;
        }

        @media (max-width: 768px) {
            .quiz-stats .row>div {
                margin-bottom: 0.5rem;
            }

            .card-title {
                font-size: 1rem;
            }

            .btn {
                font-size: 0.85rem;
                padding: 0.4rem 0.8rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="body-wrapper">
        <div class="container mt-5 pt-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>🎯 Quiz Tersedia</h2>
            </div>

            <!-- Search Bar -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <input type="text" id="search-quiz" class="form-control search-bar" placeholder="🔍 Cari quiz...">
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Quiz Grid -->
            <div class="row" id="quiz-list">
                @forelse ($quizzes as $quiz)
                    <div class="col-lg-4 col-md-6 col-sm-12 quiz-card" data-title="{{ strtolower($quiz->title) }}">
                        <div class="card shadow-sm">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="card-title flex-grow-1">{{ $quiz->title }}</h5>
                                    <span class="badge bg-success ms-2">Aktif</span>
                                </div>

                                @php
                                    $userAttempts = \App\Models\QuizAttempt::where('quiz_id', $quiz->id)
                                        ->where('user_id', auth()->id())
                                        ->count();

                                    $bestScore =
                                        \App\Models\QuizAttempt::where('quiz_id', $quiz->id)
                                            ->where('user_id', auth()->id())
                                            ->max('score') ?? 0;

                                    $lastAttempt = \App\Models\QuizAttempt::where('quiz_id', $quiz->id)
                                        ->where('user_id', auth()->id())
                                        ->latest()
                                        ->first();

                                    $isPassed = $lastAttempt && $lastAttempt->score >= $quiz->passing_score;
                                    $difficulty = 'easy';
                                    if ($quiz->passing_score >= 80) {
                                        $difficulty = 'hard';
                                    } elseif ($quiz->passing_score >= 60) {
                                        $difficulty = 'medium';
                                    }
                                    $questionsCount = $quiz->questions()->count();
                                @endphp

                                <!-- Difficulty Badge -->
                                <div class="mb-3">
                                    <span class="quiz-difficulty difficulty-{{ $difficulty }}">
                                        @if ($difficulty == 'easy')
                                            Mudah
                                        @elseif($difficulty == 'medium')
                                            Sedang
                                        @else
                                            Sulit
                                        @endif
                                    </span>
                                </div>

                                <!-- Quiz Statistics -->
                                <div class="quiz-stats mb-3">
                                    <div class="row g-0">
                                        <div class="col-4 stats-item">
                                            <span class="stats-value text-primary">{{ $questionsCount }}</span>
                                            <small class="stats-label">Soal</small>
                                        </div>
                                        <div class="col-4 stats-item">
                                            <span class="stats-value text-warning">{{ $quiz->passing_score }}%</span>
                                            <small class="stats-label">Lulus</small>
                                        </div>
                                        <div class="col-4 stats-item">
                                            <span
                                                class="stats-value {{ $quiz->time_limit ? 'text-danger' : 'text-success' }}">
                                                {{ $quiz->time_limit ? $quiz->time_limit . 'm' : '∞' }}
                                            </span>
                                            <small class="stats-label">Waktu</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quiz Information -->
                                <div class="quiz-info mb-3 flex-grow-1">
                                    <p class="card-text mb-2">
                                        <strong>Kursus:</strong> {{ $quiz->course->title ?? 'N/A' }}
                                    </p>

                                    @if ($quiz->material)
                                        <p class="card-text mb-2">
                                            <strong>Materi:</strong> {{ $quiz->material->title }}
                                        </p>
                                    @endif

                                    <p class="card-text mb-2">
                                        <strong>Percobaan:</strong> {{ $userAttempts }}/{{ $quiz->max_attempts }}
                                    </p>

                                    @if ($bestScore > 0)
                                        <p class="card-text mb-2">
                                            <strong>Skor Terbaik:</strong>
                                            <span
                                                class="score-badge fw-bold {{ $isPassed ? 'text-success' : 'text-danger' }}">
                                                {{ number_format($bestScore, 1) }}%
                                                @if ($isPassed)
                                                    <span class="text-success">✅</span>
                                                @else
                                                    <span class="text-danger">❌</span>
                                                @endif
                                            </span>
                                        </p>
                                    @endif

                                    @if ($quiz->description)
                                        <p class="card-text">
                                            <strong>Deskripsi:</strong>
                                            <span class="text-muted">{{ Str::limit($quiz->description, 80) }}</span>
                                        </p>
                                    @endif
                                </div>

                                <!-- Action Buttons -->
                                <div class="mt-auto">
                                    <div class="d-grid gap-2">
                                        @if ($userAttempts >= $quiz->max_attempts)
                                            <button class="btn btn-secondary btn-sm" disabled>
                                                <i class="fas fa-ban me-1"></i> Batas Percobaan Habis
                                            </button>
                                            <a href="{{ route('student.quizzes.show', $quiz) }}"
                                                class="btn btn-info btn-sm">
                                                <i class="fas fa-chart-line me-1"></i> Lihat Hasil
                                            </a>
                                        @elseif($isPassed)
                                            <a href="{{ route('student.quizzes.show', $quiz) }}"
                                                class="btn btn-success btn-sm">
                                                <i class="fas fa-check-circle me-1"></i> Sudah Lulus - Lihat Detail
                                            </a>
                                        @else
                                            <a href="{{ route('student.quizzes.show', $quiz) }}"
                                                class="btn btn-primary btn-sm">
                                                <i class="fas fa-play me-1"></i>
                                                {{ $userAttempts > 0 ? 'Coba Lagi' : 'Mulai Quiz' }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body empty-state">
                                <div class="mb-4">
                                    <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
                                    <h5 class="text-muted">🎯 Belum ada quiz tersedia</h5>
                                    <p class="text-muted">Quiz akan muncul setelah Anda mendaftar ke kursus yang memiliki
                                        quiz.</p>
                                    <a href="{{ route('features.course.index') }}" class="btn btn-primary">
                                        <i class="fas fa-search me-1"></i> Jelajahi Kursus
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if ($quizzes->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $quizzes->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-quiz');
            const quizCards = document.querySelectorAll('.quiz-card');

            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();

                quizCards.forEach(card => {
                    const title = card.getAttribute('data-title');
                    const shouldShow = query === '' || title.includes(query);
                    card.style.display = shouldShow ? 'block' : 'none';
                });
            });

            document.querySelectorAll('a[href*="quizzes"]').forEach(link => {
                link.addEventListener('click', function() {
                    if (!this.classList.contains('btn-secondary')) {
                        const originalText = this.innerHTML;
                        this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Loading...';
                        this.classList.add('disabled');

                        setTimeout(() => {
                            this.innerHTML = originalText;
                            this.classList.remove('disabled');
                        }, 3000);
                    }
                });
            });
        });
    </script>
@endpush
