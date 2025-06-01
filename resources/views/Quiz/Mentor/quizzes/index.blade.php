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

        .btn-info {
            background-color: #17a2b8;
            border: none;
            font-weight: 600;
        }

        .btn-info:hover {
            background-color: #138496;
        }

        .badge {
            font-size: 0.8rem;
            padding: 0.5rem 0.8rem;
        }

        .quiz-stats {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        .quiz-card {
            min-height: 280px;
        }

        .attempts-indicator {
            background: linear-gradient(45deg, #17a2b8, #138496);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }
    </style>
@endpush

@section('content')
    <div class="body-wrapper">
        <div class="container mt-5 pt-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>📝 Kelola Quiz Saya</h2>
                <a href="{{ route('mentor.quizzes.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Quiz Baru
                </a>
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

            <!-- Quiz Grid -->
            <div class="row" id="quiz-list">
                @forelse ($quizzes as $quiz)
                    <div class="col-md-4 mb-4 quiz-card" data-title="{{ strtolower($quiz->title) }}">
                        <div class="card shadow-sm h-100">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <h5 class="card-title">{{ $quiz->title }}</h5>
                                    <div class="d-flex flex-column align-items-end gap-2">
                                        <span class="badge {{ $quiz->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $quiz->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                        @if ($quiz->quiz_attempts_count > 0)
                                            <span class="attempts-indicator">
                                                <i class="fas fa-chart-line"></i> {{ $quiz->quiz_attempts_count }} attempts
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="quiz-stats mb-3">
                                    <div class="row text-center">
                                        <div class="col-4">
                                            <div class="fw-bold text-primary">{{ $quiz->questions_count ?? 0 }}</div>
                                            <small class="text-muted">Soal</small>
                                        </div>
                                        <div class="col-4">
                                            <div class="fw-bold text-success">{{ $quiz->max_attempts }}</div>
                                            <small class="text-muted">Max Coba</small>
                                        </div>
                                        <div class="col-4">
                                            <div class="fw-bold text-warning">{{ $quiz->passing_score }}%</div>
                                            <small class="text-muted">Lulus</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <p class="card-text">
                                        <strong>Kursus:</strong> {{ $quiz->course->title ?? 'N/A' }}<br>
                                        @if ($quiz->material)
                                            <strong>Materi:</strong> {{ $quiz->material->title }}<br>
                                        @endif
                                        @if ($quiz->time_limit)
                                            <strong>Waktu:</strong> {{ $quiz->time_limit }} menit<br>
                                        @endif
                                        @if ($quiz->description)
                                            <strong>Deskripsi:</strong> {{ Str::limit($quiz->description, 50) }}
                                        @endif
                                    </p>
                                </div>

                                <div class="mt-auto">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('mentor.quizzes.show', $quiz) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye"></i> Lihat Detail
                                        </a>

                                        <!-- Analyze Button -->
                                        <a href="{{ route('mentor.quizzes.analyze', $quiz) }}"
                                            class="btn btn-info btn-sm {{ $quiz->quiz_attempts_count == 0 ? 'disabled' : '' }}"
                                            {{ $quiz->quiz_attempts_count == 0 ? 'data-bs-toggle=tooltip data-bs-placement=top title=Belum ada attempt untuk dianalisis' : '' }}>
                                            <i class="fas fa-chart-bar"></i>
                                            Analisis Hasil
                                            @if ($quiz->quiz_attempts_count > 0)
                                                ({{ $quiz->quiz_attempts_count }})
                                            @endif
                                        </a>

                                        <div class="btn-group w-100" role="group">
                                            <div class="d-flex gap-2 w-100">
                                                <a href="{{ route('mentor.quizzes.edit', $quiz) }}"
                                                    class="btn btn-warning btn-sm w-100">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <form action="{{ route('mentor.quizzes.destroy', $quiz) }}" method="POST"
                                                    class="w-100"
                                                    onsubmit="return confirm('Yakin ingin menghapus quiz ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm w-100">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card text-center">
                            <div class="card-body py-5">
                                <h5 class="text-muted">📝 Belum ada quiz</h5>
                                <p class="text-muted">Mulai buat quiz pertama Anda!</p>
                                <a href="{{ route('mentor.quizzes.create') }}" class="btn btn-primary">
                                    Buat Quiz Sekarang
                                </a>
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

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase();

                quizCards.forEach(card => {
                    const title = card.getAttribute('data-title');
                    card.style.display = title.includes(query) ? 'block' : 'none';
                });
            });
        });
    </script>
@endpush
