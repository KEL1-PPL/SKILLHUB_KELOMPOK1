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

        .btn-warning {
            background-color: #ffc107;
            border: none;
            font-weight: 600;
            color: #023246;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
            font-weight: 600;
        }

        .badge {
            font-size: 0.9rem;
            padding: 0.5rem 0.8rem;
        }

        .quiz-info {
            background: linear-gradient(135deg, #287094, #023246);
            color: white;
            border-radius: 10px;
            padding: 2rem;
        }

        .question-card {
            border-left: 4px solid #287094;
            transition: transform 0.2s ease;
        }

        .question-card:hover {
            transform: translateX(5px);
        }

        .option-item {
            background-color: #f8f9fa;
            border-radius: 6px;
            padding: 0.5rem 0.8rem;
            margin: 0.2rem 0;
            border-left: 3px solid transparent;
        }

        .option-correct {
            border-left-color: #28a745;
            background-color: #d4edda;
        }

        .option-incorrect {
            border-left-color: #dc3545;
            background-color: #f8d7da;
        }

        .stats-card {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 10px;
            padding: 1.5rem;
            text-align: center;
        }

        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            color: #287094;
        }
    </style>
@endpush

@section('content')
    <div class="body-wrapper">
        <div class="container mt-5 pt-5">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1>📝 {{ $quiz->title }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('mentor.quizzes.index') }}">Quiz</a>
                            </li>
                            <li class="breadcrumb-item active">{{ $quiz->title }}</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('mentor.quizzes.questions.create', $quiz) }}" class="btn btn-success me-2">
                        <i class="fas fa-plus"></i> Tambah Soal
                    </a>
                    <a href="{{ route('mentor.quizzes.edit', $quiz) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit"></i> Edit Quiz
                    </a>
                    <a href="{{ route('mentor.quizzes.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <!-- Quiz Information -->
                <div class="col-lg-4 mb-4">
                    <div class="card quiz-info p-4"> <!-- Tambahkan padding di sini -->
                        <h3 class="mb-3">📊 Informasi Quiz</h3>
                        <div class="mb-3">
                            <strong>Status:</strong>
                            <span class="badge {{ $quiz->is_active ? 'bg-success' : 'bg-secondary' }} ms-2">
                                {{ $quiz->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                        <div class="mb-3">
                            <strong>Kursus:</strong><br>
                            {{ $quiz->course->title ?? 'N/A' }}
                        </div>
                        @if ($quiz->material)
                            <div class="mb-3">
                                <strong>Materi:</strong><br>
                                {{ $quiz->material->title }}
                            </div>
                        @endif
                        @if ($quiz->description)
                            <div class="mb-3">
                                <strong>Deskripsi:</strong><br>
                                {{ $quiz->description }}
                            </div>
                        @endif
                        <div class="mb-3">
                            <strong>Batas Waktu:</strong><br>
                            {{ $quiz->time_limit ? $quiz->time_limit . ' menit' : 'Tidak ada batas' }}
                        </div>
                        <div class="mb-3">
                            <strong>Maksimal Percobaan:</strong><br>
                            {{ $quiz->max_attempts }} kali
                        </div>
                        <div class="mb-3">
                            <strong>Nilai Lulus:</strong><br>
                            {{ $quiz->passing_score }}%
                        </div>
                        <div class="mb-3">
                            <strong>Dibuat:</strong><br>
                            {{ $quiz->created_at->format('d M Y, H:i') }}
                        </div>
                    </div>
                </div>

                <!-- Questions List -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">Daftar Soal</h3>
                            @if ($quiz->questions->count() > 0)
                                <span class="badge bg-info">{{ $quiz->questions->count() }} soal</span>
                            @endif
                        </div>
                        <div class="card-body">
                            @forelse($quiz->questions as $index => $question)
                                <div class="question-card card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div class="flex-grow-1">
                                                <h5 class="mb-2">
                                                    <span class="badge bg-primary me-2">{{ $index + 1 }}</span>
                                                    {{ $question->question }}
                                                </h5>
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="badge bg-secondary me-2">
                                                        @if ($question->type == 'multiple_choice')
                                                            📝 Pilihan Ganda
                                                        @elseif($question->type == 'true_false')
                                                            ✅ Benar/Salah
                                                        @else
                                                            📄 Essay
                                                        @endif
                                                    </span>
                                                    <span class="badge bg-warning">{{ $question->points }} poin</span>
                                                </div>
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                    type="button" data-bs-toggle="dropdown">
                                                    <i class="fas fa-cog"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('mentor.quizzes.questions.edit', [$quiz, $question]) }}">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <form
                                                            action="{{ route('mentor.quizzes.questions.destroy', [$quiz, $question]) }}"
                                                            method="POST" class="d-inline"
                                                            onsubmit="return confirm('Yakin ingin menghapus soal ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger">
                                                                <i class="fas fa-trash"></i> Hapus
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        @if ($question->type != 'essay')
                                            <div class="mt-3">
                                                <h6 class="mb-2">Pilihan Jawaban:</h6>
                                                @foreach ($question->options as $option)
                                                    <div
                                                        class="option-item {{ $option->is_correct ? 'option-correct' : '' }}">
                                                        <div class="d-flex align-items-center">
                                                            @if ($option->is_correct)
                                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                            @else
                                                                <i class="far fa-circle text-muted me-2"></i>
                                                            @endif
                                                            {{ $option->option_text }}
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5">
                                    <div class="mb-3">
                                        <i class="fas fa-question-circle fa-3x text-muted"></i>
                                    </div>
                                    <h5 class="text-muted">Belum ada soal</h5>
                                    <p class="text-muted">Mulai tambahkan soal untuk quiz ini.</p>
                                    <a href="{{ route('mentor.quizzes.questions.create', $quiz) }}"
                                        class="btn btn-success">
                                        <i class="fas fa-plus"></i> Tambah Soal Pertama
                                    </a>
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
