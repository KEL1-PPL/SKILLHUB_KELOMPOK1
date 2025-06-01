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

        .btn-secondary {
            background-color: #6c757d;
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

        .score-display-card {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
        }

        .score-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 2rem;
            margin: 0 auto 1rem;
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

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(50px, 2fr));
            gap: 1rem;
            margin-top: 2rem;
        }

        .stats-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }

        .stats-card:hover {
            transform: translateY(-2px);
        }

        .question-review {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }

        .question-review:hover {
            transform: translateX(5px);
        }

        .question-review.correct {
            border-left: 4px solid #28a745;
        }

        .question-review.incorrect {
            border-left: 4px solid #dc3545;
        }

        .question-review.essay {
            border-left: 4px solid #17a2b8;
        }

        .question-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .question-number {
            background-color: #287094;
            color: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            flex-shrink: 0;
        }

        .question-number.correct {
            background-color: #28a745;
        }

        .question-number.incorrect {
            background-color: #dc3545;
        }

        .question-number.essay {
            background-color: #17a2b8;
        }

        .question-text {
            flex-grow: 1;
            margin: 0 1rem;
            font-weight: 600;
        }

        .question-points {
            background-color: #f8f9fa;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            color: #023246;
        }

        .answer-section {
            margin-top: 1rem;
        }

        .answer-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #023246;
        }

        .answer-text {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 0.5rem;
        }

        .answer-text.essay-answer {
            min-height: 60px;
            font-style: italic;
        }

        .badge-success {
            background-color: #28a745;
        }

        .badge-danger {
            background-color: #dc3545;
        }

        .text-muted {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .quiz-info-card {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 10px;
            padding: 1.5rem;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 2rem;
        }

        .action-buttons .btn {
            min-width: 150px;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.02);
            }
        }

        @media print {
            body * {
                visibility: hidden;
            }

            .result-container,
            .result-container * {
                visibility: visible;
            }

            .action-buttons {
                display: none !important;
            }

            .question-review {
                page-break-inside: avoid;
                margin-bottom: 15px;
            }

            .stats-grid {
                display: flex !important;
                flex-wrap: wrap;
                gap: 10px;
            }

            .stats-card {
                flex: 1;
                min-width: 150px;
            }

            h2,
            h3 {
                color: #000 !important;
            }

            .question-number {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }

            .badge {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
        }
    </style>
@endpush

@section('content')
    <div class="body-wrapper">
        <div class="container mt-5 pt-5">
            <!-- Result Header -->
            <div class="quiz-header mb-4">
                <h1 class="mb-3">📊 Hasil Quiz: {{ $quiz->title }}</h1>
                <p class="mb-0">Percobaan ke-{{ $attempt->attempt_number }} dari {{ $quiz->max_attempts }}</p>
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
                    <li class="breadcrumb-item">
                        <a href="{{ route('student.quizzes.show', $quiz) }}">{{ $quiz->title }}</a>
                    </li>
                    <li class="breadcrumb-item active">Hasil</li>
                </ol>
            </nav>

            <!-- Score Display -->
            <div class="card score-display-card mb-4 p-4 text-center">
                <div class="mb-3">
                    @if ($attempt->is_passed)
                        <span class="badge bg-success fs-6">✅ LULUS</span>
                    @else
                        <span class="badge bg-danger fs-6">❌ BELUM LULUS</span>
                    @endif
                </div>
                <div class="score-circle {{ $attempt->is_passed ? 'score-passed' : 'score-failed' }} !text-xl !font-black"
                    style="font-size: 6rem !important; font-weight: 900">
                    {{ number_format($attempt->score, 1) }}%
                </div>

                <!-- Statistics Grid -->
                <div class="!flex !flex-row !justify-center !items-center !gap-4 !w-full !mx-auto"
                    style="display: flex !important; flex-direction: row !important; justify-content: center !important; align-items: center !important; margin: 0 auto !important; text-align: center !important;">
                    <div class="flex-1 min-w-0 bg-white rounded-lg shadow-md p-6 text-center">
                        <div class="text-3xl mb-2">✅</div>
                        <div class="text-2xl font-bold text-green-600">{{ $attempt->correct_answers }}</div>
                        <div class="text-gray-500 text-sm">Jawaban Benar</div>
                    </div>

                    <div class="flex-1 min-w-0 bg-white rounded-lg shadow-md p-6 text-center">
                        <div class="text-3xl mb-2">❌</div>
                        <div class="text-2xl font-bold text-red-600">
                            {{ $attempt->total_questions - $attempt->correct_answers }}</div>
                        <div class="text-gray-500 text-sm">Jawaban Salah</div>
                    </div>

                    <div class="flex-1 min-w-0 bg-white rounded-lg shadow-md p-6 text-center">
                        <div class="text-3xl mb-2">📝</div>
                        <div class="text-2xl font-bold text-blue-600">{{ $attempt->total_questions }}</div>
                        <div class="text-gray-500 text-sm">Total Soal</div>
                    </div>

                    <div class="flex-1 min-w-0 bg-white rounded-lg shadow-md p-6 text-center">
                        <div class="text-3xl mb-2">🎯</div>
                        <div class="text-2xl font-bold text-yellow-600">{{ $quiz->passing_score }}%</div>
                        <div class="text-gray-500 text-sm">Nilai Lulus</div>
                    </div>
                </div>

                <!-- Status Message -->
                @if ($attempt->is_passed)
                    <div class="alert alert-success mt-4" role="alert">
                        <i class="fas fa-trophy me-2"></i>
                        <strong>Selamat!</strong> Anda telah berhasil lulus quiz ini dengan skor
                        <strong>{{ number_format($attempt->score, 1) }}%</strong>
                    </div>
                @else
                    <div class="alert alert-danger mt-4" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Belum Lulus!</strong> Anda memerlukan minimal {{ $quiz->passing_score }}% untuk lulus.
                        @if ($attempt->attempt_number < $quiz->max_attempts)
                            Anda masih memiliki {{ $quiz->max_attempts - $attempt->attempt_number }} percobaan lagi.
                        @endif
                    </div>
                @endif
            </div>

            <div class="row">
                <!-- Detailed Review -->
                <div class="col-lg-8 mb-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">📋 Review Jawaban</h3>
                            <span class="badge bg-info">{{ count($attempt->answers) }} soal</span>
                        </div>
                        <div class="card-body">
                            @foreach ($attempt->answers as $index => $answer)
                                @php
                                    $question = $answer->question;
                                    $isCorrect = $answer->is_correct;
                                    $isEssay = $question->type === 'essay';
                                    $cardClass = $isEssay ? 'essay' : ($isCorrect ? 'correct' : 'incorrect');
                                @endphp

                                <div class="question-review {{ $cardClass }} p-2">
                                    <div class="question-header">
                                        <div class="question-number {{ $cardClass }} !font-semibold !text-lg"
                                            style="font-family: 'Inter', 'Segoe UI', 'Roboto', sans-serif !important; font-weight: 600 !important; font-size: 1.125rem !important; line-height: 1.4 !important; letter-spacing: -0.025em !important;">
                                            <h4 class="!font-semibold !text-lg !m-0"
                                                style="font-family: inherit !important; font-weight: 600 !important; font-size: 1.125rem !important; margin: 0 !important; line-height: 1.4 !important;">
                                                {{ $index + 1 }}.
                                                <span class="!font-medium"
                                                    style="font-weight: 500 !important;">{{ $question->question }}</span>
                                                <span class="!font-semibold !text-sm !opacity-75"
                                                    style="font-weight: 600 !important; font-size: 0.875rem !important; opacity: 0.75 !important;">{{ $answer->points_earned ?? 0 }}/{{ $question->points }}
                                                    poin</span>
                                            </h4>
                                        </div>

                                    </div>

                                    @if ($question->type === 'essay')
                                        <div class="answer-section">
                                            <div class="answer-label">📝 Jawaban Anda:</div>
                                            <div class="answer-text essay-answer">
                                                {{ $answer->essay_answer ?: 'Tidak ada jawaban' }}
                                            </div>
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Jawaban essay akan dinilai oleh mentor
                                            </small>
                                        </div>
                                    @else
                                        <div class="answer-section">
                                            <div class="answer-label">Jawaban Anda:</div>
                                            <div class="answer-text">
                                                {{ $answer->selectedOption ? $answer->selectedOption->option_text : 'Tidak dijawab' }}
                                                @if ($answer->selectedOption)
                                                    <span
                                                        class="badge {{ $isCorrect ? 'badge-success' : 'badge-danger' }} ms-2">
                                                        {{ $isCorrect ? 'Benar' : 'Salah' }}
                                                    </span>
                                                @endif
                                            </div>

                                            @if (!$isCorrect)
                                                @php
                                                    $correctOption = $question->options
                                                        ->where('is_correct', true)
                                                        ->first();
                                                @endphp
                                                @if ($correctOption)
                                                    <div class="answer-label mt-3">✅ Jawaban Benar:</div>
                                                    <div class="answer-text" style="background-color: #d4edda;">
                                                        {{ $correctOption->option_text }}
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Quiz Information -->
                <div class="col-lg-4 mb-4">
                    <div class="card quiz-info-card">
                        <div class="card-header">
                            <h3 class="mb-0">ℹ️ Informasi Quiz</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Kursus:</strong>
                                <div class="text-muted">{{ $quiz->course->title }}</div>
                            </div>
                            @if ($quiz->material)
                                <div class="mb-3">
                                    <strong>Materi:</strong>
                                    <div class="text-muted">{{ $quiz->material->title }}</div>
                                </div>
                            @endif
                            <div class="mb-3">
                                <strong>Waktu Mulai:</strong>
                                <div class="text-muted">{{ $attempt->started_at->format('d M Y, H:i') }}</div>
                            </div>
                            <div class="mb-3">
                                <strong>Waktu Selesai:</strong>
                                <div class="text-muted">{{ $attempt->submitted_at->format('d M Y, H:i') }}</div>
                            </div>
                            <div class="mb-3">
                                <strong>Durasi Pengerjaan:</strong>
                                <div class="text-muted">
                                    {{ $attempt->started_at->diffForHumans($attempt->submitted_at, true) }}</div>
                            </div>
                            @if ($quiz->time_limit)
                                <div class="mb-3">
                                    <strong>Batas Waktu:</strong>
                                    <div class="text-muted">{{ $quiz->time_limit }} menit</div>
                                </div>
                            @endif
                            <div class="mb-3">
                                <strong>Percobaan:</strong>
                                <span
                                    class="badge bg-info">{{ $attempt->attempt_number }}/{{ $quiz->max_attempts }}</span>
                            </div>
                            <div class="mb-4">
                                <strong>Status:</strong>
                                <span class="badge {{ $attempt->is_passed ? 'bg-success' : 'bg-danger' }}">
                                    {{ $attempt->is_passed ? 'Lulus' : 'Tidak Lulus' }}
                                </span>
                            </div>

                            <!-- Action Buttons dipindah ke dalam card -->
                            <div class="!border-t !pt-4 !mt-4"
                                style="border-top: 1px solid #dee2e6 !important; padding-top: 1rem !important; margin-top: 1rem !important;">
                                <div class="!d-grid !gap-2" style="display: grid !important; gap: 0.5rem !important;">
                                    <a href="{{ route('student.quizzes.show', $quiz) }}" class="btn btn-primary !w-100"
                                        style="width: 100% !important;">
                                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Quiz
                                    </a>

                                    @if (!$attempt->is_passed && $attempt->attempt_number < $quiz->max_attempts)
                                        <a href="{{ route('student.quizzes.start', $quiz) }}"
                                            class="btn btn-success !w-100" style="width: 100% !important;">
                                            <i class="fas fa-redo me-2"></i>Coba Lagi
                                        </a>
                                    @endif

                                    <a href="{{ route('student.quizzes.index') }}" class="btn btn-secondary !w-100"
                                        style="width: 100% !important;">
                                        <i class="fas fa-list me-2"></i>Semua Quiz
                                    </a>

                                    @if ($attempt->is_passed)
                                        <button class="btn btn-success !w-100" onclick="openCertificatePopup()"
                                            style="width: 100% !important;">
                                            <i class="fas fa-certificate me-2"></i>Cetak Sertifikat
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal Sertifikat -->
    <div id="certificateModal"
        style="display: none; position: fixed; top: 0; left:0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
        <div id="certificateContent"
            style="background: white; padding: 30px; border-radius: 12px; width: 80%; max-width: 800px;">
            <h2 style="text-align: center;">SERTIFIKAT KELULUSAN</h2>
            <p style="text-align: center;">Diberikan kepada:</p>
            <h3 style="text-align: center;">{{ Auth::user()->name }}</h3>
            <p style="text-align: center;">Telah menyelesaikan kuis:</p>
            <h4 style="text-align: center;">"{{ $quiz->title }}"</h4>
            <p style="text-align: center;">Dengan skor: <strong>{{ number_format($attempt->score, 1) }}%</strong></p>
            <p style="text-align: center;">Tanggal: {{ now()->format('d M Y') }}</p>
            <div style="margin-top: 30px; text-align: center;">
                <button onclick="printCertificate()" class="btn btn-primary">
                    <i class="fas fa-print me-2"></i>Print Sertifikat
                </button>
                <button onclick="closeCertificatePopup()" class="btn btn-secondary">
                    <i class="fas fa-times me-2"></i>Tutup
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        @if ($attempt->is_passed)
            function createConfetti() {
                const colors = ['#287094', '#023246', '#28a745', '#ffc107'];

                for (let i = 0; i < 50; i++) {
                    setTimeout(() => {
                        const confetti = document.createElement('div');
                        confetti.style.position = 'fixed';
                        confetti.style.left = Math.random() * 100 + 'vw';
                        confetti.style.top = '-10px';
                        confetti.style.width = '10px';
                        confetti.style.height = '10px';
                        confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                        confetti.style.pointerEvents = 'none';
                        confetti.style.zIndex = '9999';
                        confetti.style.borderRadius = '50%';

                        document.body.appendChild(confetti);

                        const animation = confetti.animate([{
                                transform: 'translateY(-10px) rotate(0deg)',
                                opacity: 1
                            },
                            {
                                transform: 'translateY(100vh) rotate(360deg)',
                                opacity: 0
                            }
                        ], {
                            duration: 3000 + Math.random() * 2000,
                            easing: 'cubic-bezier(0.5, 0, 0.5, 1)'
                        });

                        animation.onfinish = () => confetti.remove();
                    }, i * 100);
                }
            }

            setTimeout(createConfetti, 1000);
        @endif

        @if (!$attempt->is_passed)
            document.addEventListener('DOMContentLoaded', function() {
                const firstIncorrect = document.querySelector('.question-review.incorrect');
                if (firstIncorrect) {
                    setTimeout(() => {
                        firstIncorrect.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                        firstIncorrect.style.animation = 'pulse 2s ease-in-out';
                    }, 2000);
                }
            });
        @endif

        function openCertificatePopup() {
            document.getElementById('certificateModal').style.display = 'flex';
        }

        function closeCertificatePopup() {
            document.getElementById('certificateModal').style.display = 'none';
        }

        function printCertificate() {
            const content = document.getElementById('certificateContent').innerHTML;
            const win = window.open('', '', 'width=900,height=650');

            win.document.write(`
                <html>
                    <head>
                        <title>Sertifikat Kelulusan</title>
                        <style>
                            body { font-family: 'Arial', sans-serif; text-align: center; padding: 40px; }
                            h2 { font-size: 32px; margin-bottom: 0; }
                            h3 { font-size: 26px; margin: 10px 0; }
                            h4 { font-size: 22px; margin-top: 0; }
                            p { font-size: 18px; }
                            .sertifikat-box {
                                border: 5px double #444;
                                padding: 50px;
                                border-radius: 20px;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="sertifikat-box">
                            ${content}
                        </div>
                        <script>
                            window.onload = function() {
                                window.print();
                                setTimeout(() => window.close(), 100);
                            };
                        <\/script>
                    </body>
                </html>
            `);
            win.document.close();
        }
    </script>
@endpush
