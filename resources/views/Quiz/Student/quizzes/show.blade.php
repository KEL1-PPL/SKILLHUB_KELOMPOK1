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

        h1,
        h2,
        h3 {
            color: #023246;
            font-weight: 700;
        }

        .card {
            background-color: #FFFFFF;
            border: 1px solid #D4D4CE;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background-color: #287094;
            border: none;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #023246;
        }

        .btn-success {
            background-color: #28a745;
            border: none;
            font-weight: 600;
        }

        .btn-info {
            background-color: #17a2b8;
            border: none;
            font-weight: 600;
        }

        .badge {
            font-size: 0.9rem;
            padding: 0.5rem 0.8rem;
        }

        .quiz-header {
            background: linear-gradient(135deg, #287094, #023246);
            color: white;
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
        }

        .quiz-info-card {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 10px;
            padding: 1.5rem;
        }

        .attempt-card {
            border-left: 4px solid #287094;
            transition: transform 0.2s ease;
        }

        .attempt-card:hover {
            transform: translateX(5px);
        }

        .score-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
            margin: 0 auto;
        }

        .score-passed {
            background-color: #d4edda;
            color: #155724;
            border: 3px solid #28a745;
        }

        .score-failed {
            background-color: #f8d7da;
            color: #721c24;
            border: 3px solid #dc3545;
        }

        .prerequisite-alert {
            background: linear-gradient(135deg, #fff3cd, #ffeaa7);
            border: 1px solid #ffc107;
            border-radius: 10px;
            padding: 1.5rem;
        }

        .countdown-timer {
            background-color: #dc3545;
            color: white;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 700;
            font-size: 1.1rem;
        }
    </style>
@endpush

@section('content')
    <div class="body-wrapper">
        <div class="container mt-5 pt-5">
            <!-- Header -->
            <div class="quiz-header mb-4">
                <h1 class="mb-3">🎯 {{ $quiz->title }}</h1>
                <p class="mb-0">{{ $quiz->course->title }}</p>
                @if ($quiz->material)
                    <small class="opacity-75">Materi: {{ $quiz->material->title }}</small>
                @endif
            </div>

            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('student.quizzes.index') }}">Quiz</a>
                    </li>
                    <li class="breadcrumb-item active">{{ $quiz->title }}</li>
                </ol>
            </nav>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-clock me-2"></i>{{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <!-- Quiz Information -->
                <div class="col-lg-4 mb-4">
                    <div class="card quiz-info-card p-4 m-2">
                        <h3 class="mb-3">📋 Detail Quiz</h3>

                        @if ($quiz->description)
                            <div class="mb-3">
                                <strong>Deskripsi:</strong>
                                <p class="mt-1 text-muted">{{ $quiz->description }}</p>
                            </div>
                        @endif

                        <div class="row text-center mb-3">
                            <div class="col-4">
                                <div class="fw-bold text-primary fs-4">{{ $quiz->questions->count() }}</div>
                                <small class="text-muted">Soal</small>
                            </div>
                            <div class="col-4">
                                <div class="fw-bold text-warning fs-4">{{ $quiz->passing_score }}%</div>
                                <small class="text-muted">Lulus</small>
                            </div>
                            <div class="col-4">
                                <div class="fw-bold {{ $quiz->time_limit ? 'text-danger' : 'text-success' }} fs-4">
                                    {{ $quiz->time_limit ? $quiz->time_limit . 'm' : '∞' }}
                                </div>
                                <small class="text-muted">Waktu</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <strong>Maksimal Percobaan:</strong>
                            <span class="badge bg-info ms-2">{{ $quiz->max_attempts }} kali</span>
                        </div>

                        <div class="mb-3">
                            <strong>Percobaan Anda:</strong>
                            <span
                                class="badge {{ $attempts->count() >= $quiz->max_attempts ? 'bg-danger' : 'bg-success' }} ms-2">
                                {{ $attempts->count() }}/{{ $quiz->max_attempts }}
                            </span>
                        </div>

                        @php
                            $bestScore = $attempts->max('score') ?? 0;
                            $lastAttempt = $attempts->first();
                            $isPassed = $lastAttempt && $lastAttempt->is_passed;
                        @endphp

                        @if ($bestScore > 0)
                            <div class="text-center mt-4">
                                <h5>Skor Terbaik</h5>
                                <div class="score-circle {{ $isPassed ? 'score-passed' : 'score-failed' }}">
                                    {{ number_format($bestScore, 1) }}%
                                </div>
                                <div class="mt-2">
                                    @if ($isPassed)
                                        <span class="badge bg-success">✅ LULUS</span>
                                    @else
                                        <span class="badge bg-danger">❌ BELUM LULUS</span>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Action Button -->
                        <div class="mt-4 d-grid">
                            @if (!$canTakeQuiz)
                                <button class="btn btn-secondary" disabled>
                                    <i class="fas fa-ban"></i> Batas Percobaan Habis
                                </button>
                            @elseif($isPassed)
                                <a href="{{ route('student.quizzes.start', $quiz) }}" class="btn btn-info">
                                    <i class="fas fa-redo"></i> Coba Lagi (Opsional)
                                </a>
                            @else
                                <a href="{{ route('student.quizzes.start', $quiz) }}" class="btn btn-success btn-lg">
                                    <i class="fas fa-play"></i>
                                    {{ $attempts->count() > 0 ? 'Coba Lagi' : 'Mulai Quiz' }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>


                <!-- Attempt History -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">📊 Riwayat Percobaan</h3>
                            @if ($attempts->count() > 0)
                                <span class="badge bg-info">{{ $attempts->count() }} percobaan</span>
                            @endif
                        </div>
                        <div class="card-body">
                            @forelse($attempts as $attempt)
                                <div class="attempt-card card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="flex-grow-1">
                                                <h5 class="mb-2">
                                                    <span class="badge bg-primary me-2">Percobaan
                                                        #{{ $attempt->attempt_number }}</span>
                                                    @if ($attempt->is_passed)
                                                        <span class="badge bg-success">✅ LULUS</span>
                                                    @else
                                                        <span class="badge bg-danger">❌ BELUM LULUS</span>
                                                    @endif
                                                </h5>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p class="mb-1">
                                                            <strong>Skor:</strong>
                                                            <span
                                                                class="fw-bold {{ $attempt->is_passed ? 'text-success' : 'text-danger' }}">
                                                                {{ number_format($attempt->score, 1) }}%
                                                            </span>
                                                        </p>
                                                        <p class="mb-1">
                                                            <strong>Benar:</strong>
                                                            {{ $attempt->correct_answers }}/{{ $attempt->total_questions }}
                                                        </p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p class="mb-1">
                                                            <strong>Mulai:</strong>
                                                            {{ $attempt->started_at->format('d M Y, H:i') }}
                                                        </p>
                                                        @if ($attempt->submitted_at)
                                                            <p class="mb-1">
                                                                <strong>Selesai:</strong>
                                                                {{ $attempt->submitted_at->format('d M Y, H:i') }}
                                                            </p>
                                                            <p class="mb-1">
                                                                <strong>Durasi:</strong>
                                                                {{ $attempt->started_at->diffForHumans($attempt->submitted_at, true) }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                @if ($attempt->submitted_at)
                                                    <a href="{{ route('student.quizzes.result', [$quiz, $attempt]) }}"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i> Lihat Detail
                                                    </a>
                                                @else
                                                    <a href="{{ route('student.quizzes.take', [$quiz, $attempt]) }}"
                                                        class="btn btn-sm btn-warning">
                                                        <i class="fas fa-play"></i> Lanjutkan
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5">
                                    <div class="mb-3">
                                        <i class="fas fa-chart-line fa-3x text-muted"></i>
                                    </div>
                                    <h5 class="text-muted">Belum ada percobaan</h5>
                                    <p class="text-muted">Mulai quiz pertama Anda sekarang!</p>
                                    @if ($canTakeQuiz)
                                        <a href="{{ route('student.quizzes.start', $quiz) }}" class="btn btn-success">
                                            <i class="fas fa-play"></i> Mulai Quiz
                                        </a>
                                    @endif
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
@endpush
