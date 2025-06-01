@extends('all.component.app')

@section('content')
    <div class="body-wrapper">
        <div class="container mt-5 pt-5">
            <!-- Auto Save Indicator -->
            <div id="autoSaveIndicator" class="auto-save-indicator">
                ✓ Jawaban tersimpan otomatis
            </div>

            <div class="row">
                <!-- Timer and Info Sidebar -->
                <div class="col-md-3">
                    @if ($quiz->time_limit)
                        <div class="timer-card">
                            <div class="timer-display" id="timer">
                                @php
                                    $totalSeconds = $quiz->time_limit * 60;
                                    $elapsedSeconds = $attempt->started_at->diffInSeconds(now());
                                    $remainingSeconds = max(0, $totalSeconds - $elapsedSeconds);
                                    $minutes = floor($remainingSeconds / 60);
                                    $seconds = $remainingSeconds % 60;
                                @endphp
                                {{ sprintf('%02d:%02d', $minutes, $seconds) }}
                            </div>
                            <small>Waktu Tersisa</small>
                        </div>
                    @endif

                    <div class="bg-white rounded-lg shadow-lg border border-gray-200 p-4 mt-3"
                        style="font-family: 'Poppins', 'Inter', 'Segoe UI', sans-serif !important; text-rendering: optimizeLegibility !important;">
                        <div class="flex items-center mb-4">
                            <h2 class="!text-base !font-semibold !text-gray-800 !mb-0 !tracking-tight"
                                style="font-size: 1rem !important; font-weight: 600 !important; color: #1f2937 !important; margin-bottom: 0 !important; letter-spacing: -0.025em !important; line-height: 1.3 !important;">
                                <span class="!text-lg !mr-2"
                                    style="font-size: 1.125rem !important; margin-right: 0.5rem !important;">📋</span>Info
                                Quiz
                            </h2>
                        </div>

                        <div class="!space-y-4"
                            style="display: flex !important; flex-direction: column !important; gap: 1rem !important;">
                            <div class="flex flex-col">
                                <span class="!text-xs !font-medium !text-gray-500 !mb-1 !uppercase !tracking-wide"
                                    style="font-size: 0.75rem !important; font-weight: 500 !important; color: #6b7280 !important; margin-bottom: 0.25rem !important; text-transform: uppercase !important; letter-spacing: 0.05em !important;">Judul:</span>
                                <span class="!text-sm !text-gray-800 !font-medium !leading-relaxed"
                                    style="font-size: 0.875rem !important; color: #1f2937 !important; font-weight: 500 !important; line-height: 1.5 !important;">{{ $quiz->title }}</span>
                            </div>

                            <div class="flex flex-col">
                                <span class="!text-xs !font-medium !text-gray-500 !mb-1 !uppercase !tracking-wide"
                                    style="font-size: 0.75rem !important; font-weight: 500 !important; color: #6b7280 !important; margin-bottom: 0.25rem !important; text-transform: uppercase !important; letter-spacing: 0.05em !important;">Percobaan:</span>
                                <span class="!text-sm !text-gray-800 !leading-relaxed"
                                    style="font-size: 0.875rem !important; color: #1f2937 !important; line-height: 1.5 !important;">
                                    <span class="!font-semibold !text-blue-600"
                                        style="font-weight: 600 !important; color: #2563eb !important;">{{ $attempt->attempt_number }}</span>
                                    <span class="!font-medium !mx-1"
                                        style="font-weight: 500 !important; margin: 0 0.25rem !important;">dari</span>
                                    <span class="!font-semibold"
                                        style="font-weight: 600 !important;">{{ $quiz->max_attempts }}</span>
                                </span>
                            </div>

                            <div class="flex flex-col">
                                <span class="!text-xs !font-medium !text-gray-500 !mb-1 !uppercase !tracking-wide"
                                    style="font-size: 0.75rem !important; font-weight: 500 !important; color: #6b7280 !important; margin-bottom: 0.25rem !important; text-transform: uppercase !important; letter-spacing: 0.05em !important;">Total
                                    Soal:</span>
                                <span class="!text-sm !text-gray-800 !font-semibold"
                                    style="font-size: 0.875rem !important; color: #1f2937 !important; font-weight: 600 !important;">{{ $questions->count() }}</span>
                            </div>

                            @if ($quiz->passing_score)
                                <div class="flex flex-col !border-t !border-gray-100 !pt-4"
                                    style="border-top: 1px solid #f3f4f6 !important; padding-top: 1rem !important;">
                                    <span class="!text-xs !font-medium !text-gray-500 !mb-1 !uppercase !tracking-wide"
                                        style="font-size: 0.75rem !important; font-weight: 500 !important; color: #6b7280 !important; margin-bottom: 0.25rem !important; text-transform: uppercase !important; letter-spacing: 0.05em !important;">Nilai
                                        Lulus:</span>
                                    <span class="!text-green-600 !font-bold !text-base !tracking-tight"
                                        style="color: #059669 !important; font-weight: 700 !important; font-size: 1rem !important; letter-spacing: -0.025em !important; line-height: 1.2 !important;">{{ $quiz->passing_score }}%</span>
                                </div>
                            @endif

                            <div class="!border-t !border-gray-100 !pt-4"
                                style="border-top: 1px solid #f3f4f6 !important; padding-top: 1rem !important;">
                                <div class="!bg-blue-50 !rounded-lg !p-3 !border !border-blue-200"
                                    style="background-color: #eff6ff !important; border-radius: 0.5rem !important; padding: 0.75rem !important; border: 1px solid #bfdbfe !important;">
                                    <div class="!text-blue-800 !font-medium !text-center"
                                        style="color: #1e40af !important; font-weight: 500 !important; text-align: center !important; font-size: 0.875rem !important;">
                                        Soal <span id="currentQuestion" class="!font-semibold"
                                            style="font-weight: 600 !important;">1</span> dari <span class="!font-semibold"
                                            style="font-weight: 600 !important;">{{ $questions->count() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quiz Content -->
                <div class="col-md-9">
                    <div class="quiz-container">
                        <!-- Progress Bar -->
                        <div class="progress-indicator">
                            <div class="progress-bar" style="width: {{ (1 / $questions->count()) * 100 }}%"
                                id="progressBar"></div>
                        </div>

                        <!-- Quiz Form -->
                        <form id="quizForm" class="max-w-4xl mt-0">
                            @csrf
                            @foreach ($questions as $index => $question)
                                <div class="question-card rounded-xl p-8 mb-6" data-question="{{ $index + 1 }}"
                                    style="{{ $index > 0 ? 'display: none;' : '' }}">

                                    <!-- Question Header -->
                                    <div class="mb-8" style="margin-bottom: 2rem !important;">
                                        <h2 class="!text-lg !font-semibold !text-gray-800 !mb-4 !leading-relaxed !tracking-tight"
                                            style="font-family: 'Poppins', 'Inter', 'Segoe UI', sans-serif !important; font-size: 2rem !important; font-weight: 600 !important; color: #1f2937 !important; margin-bottom: 1rem !important; line-height: 1.6 !important; letter-spacing: -0.025em !important; text-rendering: optimizeLegibility !important;">
                                            <span
                                                class="!inline-block !bg-blue-600 !text-white !rounded-full !w-8 !h-8 !text-center !leading-8 !text-sm !font-bold !mr-3 !flex-shrink-0"
                                                style="display: inline-block !important; background-color: #2563eb !important; color: white !important; border-radius: 50% !important; width: 2rem !important; height: 2rem !important; text-align: center !important; line-height: 2rem !important; font-size: 0.875rem !important; font-weight: 700 !important; margin-right: 0.75rem !important; flex-shrink: 0 !important;">{{ $index + 1 }}</span>
                                            <span class="!inline !align-middle"
                                                style="display: inline !important; vertical-align: middle !important;">{{ $question->question }}</span>
                                        </h2>
                                    </div>

                                    <!-- Options Container -->
                                    <div class="space-y-4 mb-8">
                                        @if ($question->type === 'multiple_choice')
                                            @foreach ($question->options as $option)
                                                <div>
                                                    <label
                                                        class="flex items-center text-base p-1 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors"
                                                        for="option_{{ $question->id }}_{{ $option->id }}">
                                                        <input type="radio" name="question_{{ $question->id }}"
                                                            id="option_{{ $question->id }}_{{ $option->id }}"
                                                            value="{{ $option->id }}"
                                                            data-question-id="{{ $question->id }}"
                                                            class="w-2 h-2 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2"
                                                            {{ isset($existingAnswers[$question->id]) && $existingAnswers[$question->id] == $option->id ? 'checked' : '' }}>
                                                        <span
                                                            class="ml-2 text-base text-gray-700">{{ $option->option_text }}</span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        @elseif($question->type === 'true_false')
                                            @foreach ($question->options as $option)
                                                <label
                                                    class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors"
                                                    for="option_{{ $question->id }}_{{ $option->id }}">
                                                    <input type="radio" name="question_{{ $question->id }}"
                                                        id="option_{{ $question->id }}_{{ $option->id }}"
                                                        value="{{ $option->id }}" data-question-id="{{ $question->id }}"
                                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2"
                                                        {{ isset($existingAnswers[$question->id]) && $existingAnswers[$question->id] == $option->id ? 'checked' : '' }}>
                                                    <span
                                                        class="ml-3 text-base text-gray-700">{{ $option->option_text }}</span>
                                                </label>
                                            @endforeach
                                        @elseif($question->type === 'essay')
                                            <textarea
                                                class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-vertical min-h-32"
                                                name="essay_{{ $question->id }}" data-question-id="{{ $question->id }}"
                                                placeholder="Tuliskan jawaban Anda di sini...">{{ $existingAnswers[$question->id] ?? '' }}</textarea>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </form>

                        <!-- Navigation Buttons -->
                        <div class="navigation-buttons">
                            <button type="button" class="btn btn-secondary" id="prevBtn" onclick="changeQuestion(-1)"
                                disabled>
                                ← Sebelumnya
                            </button>
                            <button type="button" class="btn btn-primary" id="nextBtn" onclick="changeQuestion(1)">
                                Selanjutnya →
                            </button>
                            <button type="button" class="btn btn-success" id="submitBtn" onclick="submitQuiz()"
                                style="display: none;">
                                Serahkan Quiz
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let currentQuestionIndex = 0;
        const totalQuestions = {{ $questions->count() }};
        let autoSaveTimeout;
        let timerInterval;

        @if ($quiz->time_limit)
            @php
                $totalSeconds = $quiz->time_limit * 60;
                $elapsedSeconds = $attempt->started_at->diffInSeconds(now());
                $remainingSeconds = max(0, $totalSeconds - $elapsedSeconds);
            @endphp

            let remainingTime = {{ intval($remainingSeconds) }};

            console.log('Debug Timer Info:');
            console.log('Quiz time limit:', {{ $quiz->time_limit }}, 'minutes');
            console.log('Started at:', '{{ $attempt->started_at }}');
            console.log('Current server time:', '{{ now() }}');
            console.log('Initial remaining time:', remainingTime, 'seconds');

            function updateTimer() {
                if (remainingTime <= 0) {
                    clearInterval(timerInterval);
                    alert('Waktu habis! Quiz akan otomatis diserahkan.');
                    submitQuiz();
                    return;
                }

                const minutes = Math.floor(remainingTime / 60);
                const seconds = remainingTime % 60;

                const timerElement = document.getElementById('timer');
                if (timerElement) {
                    timerElement.textContent =
                        String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
                }

                if (remainingTime <= 300 && remainingTime > 0) {
                    const warningAlert = document.getElementById('timeWarning');
                    if (warningAlert) {
                        warningAlert.style.display = 'block';
                    }
                }

                remainingTime--;
            }

            timerInterval = setInterval(updateTimer, 1000);
            updateTimer();
        @endif

        function changeQuestion(direction) {
            const questions = document.querySelectorAll('.question-card');
            questions[currentQuestionIndex].style.display = 'none';
            currentQuestionIndex += direction;
            questions[currentQuestionIndex].style.display = 'block';
            document.getElementById('currentQuestion').textContent = currentQuestionIndex + 1;
            const progress = ((currentQuestionIndex + 1) / totalQuestions) * 100;
            document.getElementById('progressBar').style.width = progress + '%';
            updateNavigationButtons();
        }

        function updateNavigationButtons() {
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const submitBtn = document.getElementById('submitBtn');

            prevBtn.disabled = (currentQuestionIndex === 0);
            const isLastQuestion = (currentQuestionIndex === totalQuestions - 1);

            if (isLastQuestion) {
                nextBtn.style.display = 'none';
                submitBtn.style.display = 'inline-block';
            } else {
                nextBtn.style.display = 'inline-block';
                submitBtn.style.display = 'none';
            }
        }

        function saveAnswer(questionId, selectedOptionId = null, essayAnswer = null) {
            clearTimeout(autoSaveTimeout);

            autoSaveTimeout = setTimeout(() => {
                fetch(`{{ route('student.quizzes.save-answer', [$quiz, $attempt]) }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        },
                        body: JSON.stringify({
                            question_id: questionId,
                            selected_option_id: selectedOptionId,
                            essay_answer: essayAnswer
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showAutoSaveIndicator();
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }, 1000);
        }

        function showAutoSaveIndicator() {
            const indicator = document.getElementById('autoSaveIndicator');
            indicator.classList.add('show');
            setTimeout(() => {
                indicator.classList.remove('show');
            }, 2000);
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateNavigationButtons();

            document.querySelectorAll('input[type="radio"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    const questionId = this.getAttribute('data-question-id');
                    const selectedOptionId = this.value;
                    saveAnswer(questionId, selectedOptionId);

                    document.querySelectorAll(`input[name="${this.name}"]`).forEach(r => {
                        const optionItem = r.closest('.option-item');
                        if (optionItem) {
                            optionItem.classList.remove('selected');
                        }
                    });
                    const currentOptionItem = this.closest('.option-item');
                    if (currentOptionItem) {
                        currentOptionItem.classList.add('selected');
                    }
                });
            });

            document.querySelectorAll('textarea[name^="essay_"]').forEach(textarea => {
                textarea.addEventListener('input', function() {
                    const questionId = this.getAttribute('data-question-id');
                    const essayAnswer = this.value;
                    saveAnswer(questionId, null, essayAnswer);
                });
            });

            document.querySelectorAll('input[type="radio"]:checked').forEach(radio => {
                const optionItem = radio.closest('.option-item');
                if (optionItem) {
                    optionItem.classList.add('selected');
                }
            });
        });

        function submitQuiz() {
            if (confirm(
                    'Apakah Anda yakin ingin menyerahkan quiz? Anda tidak dapat mengubah jawaban setelah diserahkan.')) {

                if (timerInterval) {
                    clearInterval(timerInterval);
                }

                const submitBtn = document.getElementById('submitBtn');
                const nextBtn = document.getElementById('nextBtn');
                const prevBtn = document.getElementById('prevBtn');

                const originalSubmitText = submitBtn ? submitBtn.innerHTML : '';

                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyerahkan...';
                    submitBtn.disabled = true;
                }
                if (nextBtn) {
                    nextBtn.disabled = true;
                }
                if (prevBtn) {
                    prevBtn.disabled = true;
                }

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('student.quizzes.submit', [$quiz, $attempt]) }}';

                let csrfToken = document.querySelector('#quizForm input[name="_token"]');
                if (!csrfToken) {
                    csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (csrfToken) {
                        csrfToken = csrfToken.getAttribute('content');
                    }
                } else {
                    csrfToken = csrfToken.value;
                }

                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = csrfToken;
                form.appendChild(tokenInput);

                form.style.display = 'none';
                document.body.appendChild(form);

                try {
                    form.submit();
                } catch (error) {
                    console.error('Submit error:', error);
                    alert('Terjadi kesalahan saat menyerahkan quiz. Silakan coba lagi.');

                    if (submitBtn) {
                        submitBtn.innerHTML = originalSubmitText;
                        submitBtn.disabled = false;
                    }
                    if (nextBtn) {
                        nextBtn.disabled = false;
                    }
                    if (prevBtn) {
                        prevBtn.disabled = false;
                    }
                }
            }
        }

        window.addEventListener('beforeunload', function(e) {
            e.preventDefault();
            e.returnValue = '';
        });

        window.addEventListener('unload', function() {
            if (timerInterval) {
                clearInterval(timerInterval);
            }
        });
    </script>
@endpush
