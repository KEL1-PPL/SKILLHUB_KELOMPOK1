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

        .card {
            background-color: #FFFFFF;
            border: 1px solid #D4D4CE;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .form-control,
        .form-select {
            border: 2px solid #D4D4CE;
            border-radius: 8px;
            font-family: 'Figtree', sans-serif;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #287094;
            box-shadow: 0 0 0 0.2rem rgba(40, 112, 148, 0.25);
        }

        .btn-primary {
            background-color: #287094;
            border: none;
            font-weight: 600;
            border-radius: 8px;
        }

        .btn-primary:hover {
            background-color: #023246;
        }

        .btn-secondary {
            background-color: #D4D4CE;
            border: none;
            font-weight: 600;
            color: #023246;
            border-radius: 8px;
        }

        .btn-secondary:hover {
            background-color: #C0C0BA;
            color: #023246;
        }

        .btn-success {
            background-color: #28a745;
            border: none;
            font-weight: 600;
            border-radius: 8px;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
            font-weight: 600;
            border-radius: 8px;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .form-label {
            color: #023246;
            font-weight: 600;
        }

        .form-check-label {
            color: #023246;
        }

        .form-check-input:checked {
            background-color: #287094;
            border-color: #287094;
        }

        .alert {
            border-radius: 8px;
        }

        .option-item {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }

        .option-item:hover {
            border-color: #287094;
            box-shadow: 0 2px 8px rgba(40, 112, 148, 0.1);
        }

        .quiz-info {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 10px 15px;
            margin-bottom: 10px;
            color: #023246;
        }
    </style>
@endpush

@section('content')
    <div class="body-wrapper">
        <div class="container mt-5 pt-5">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-header bg-transparent border-bottom-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h2>📝 Tambah Soal Quiz</h2>
                                    <p class="text-muted mb-0">Quiz: <strong>{{ $quiz->title }}</strong></p>
                                </div>
                                <a href="{{ route('mentor.quizzes.show', $quiz) }}" class="btn btn-secondary">
                                    ← Kembali
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('mentor.quizzes.questions.store', $quiz) }}" method="POST"
                                id="questionForm">
                                @csrf

                                <!-- Question Text -->
                                <div class="mb-3">
                                    <label for="question" class="form-label">Pertanyaan *</label>
                                    <textarea name="question" id="question" rows="4" class="form-control @error('question') is-invalid @enderror"
                                        required placeholder="Masukkan pertanyaan Anda di sini...">{{ old('question') }}</textarea>
                                    @error('question')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Points -->
                                <div class="mb-4">
                                    <label for="points" class="form-label">Poin *</label>
                                    <input type="number" name="points" id="points"
                                        class="form-control @error('points') is-invalid @enderror"
                                        value="{{ old('points', 10) }}" min="1" max="100" required
                                        placeholder="10" style="max-width: 150px;">
                                    @error('points')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Multiple Choice Options -->
                                <div class="mb-4">
                                    <h5 class="mb-3">🔧 Pilihan Jawaban</h5>
                                    <div id="options_container"></div>
                                    <button type="button" id="add_option" class="btn btn-success btn-sm mt-3">
                                        Tambah Pilihan
                                    </button>
                                    @error('options')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                    @error('correct_option')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Submit Buttons -->
                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('mentor.quizzes.show', $quiz) }}" class="btn btn-secondary">
                                        Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        Simpan Soal
                                    </button>
                                </div>
                            </form>
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
            const optionsContainer = document.getElementById('options_container');
            const addOptionBtn = document.getElementById('add_option');
            let optionCounter = 0;

            addOption();
            addOption();

            addOptionBtn.addEventListener('click', addOption);

            function addOption() {
                if (optionCounter >= 6) {
                    alert('Maksimal 6 pilihan jawaban');
                    return;
                }

                optionCounter++;
                const optionHtml = `
                    <div class="option-item" data-option="${optionCounter}">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <label class="form-label">Pilihan ${optionCounter}</label>
                                <input type="text" name="options[${optionCounter-1}][text]" class="form-control" required
                                       placeholder="Masukkan pilihan jawaban..." value="${getOldOptionValue(optionCounter-1)}">
                            </div>
                            <div class="col-md-3">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="radio" name="correct_option"
                                           value="${optionCounter-1}" id="correct_${optionCounter}"
                                           ${isOldOptionCorrect(optionCounter-1) ? 'checked' : ''}>
                                    <label class="form-check-label" for="correct_${optionCounter}">
                                        Jawaban Benar
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-1">
                                ${optionCounter > 2 ? `
                                    <button type="button" class="btn btn-danger btn-sm mt-4 remove-option"
                                            onclick="removeOption(${optionCounter})">
                                        🗑️
                                    </button>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                `;
                optionsContainer.insertAdjacentHTML('beforeend', optionHtml);
                updateRemoveButtons();
            }

            function getOldOptionValue(index) {
                const oldOptions = @json(old('options', []));
                return oldOptions[index] ? oldOptions[index].text : '';
            }

            function isOldOptionCorrect(index) {
                const oldCorrect = {{ old('correct_option', 'null') }};
                return oldCorrect == index;
            }

            window.removeOption = function(optionId) {
                const optionElement = document.querySelector(`[data-option="${optionId}"]`);
                if (optionElement && optionsContainer.children.length > 2) {
                    optionElement.remove();
                    optionCounter = optionsContainer.children.length;
                    updateRemoveButtons();
                    reindexOptions();
                }
            };

            function updateRemoveButtons() {
                const removeButtons = document.querySelectorAll('.remove-option');
                removeButtons.forEach(button => {
                    button.disabled = optionsContainer.children.length <= 2;
                });
            }

            function reindexOptions() {
                const options = optionsContainer.querySelectorAll('.option-item');
                options.forEach((option, index) => {
                    option.setAttribute('data-option', index + 1);

                    const label = option.querySelector('.form-label');
                    const textInput = option.querySelector('input[type="text"]');
                    const radioInput = option.querySelector('input[type="radio"]');
                    const radioLabel = option.querySelector('.form-check-label');
                    const removeButton = option.querySelector('.remove-option');

                    label.textContent = `Pilihan ${index + 1}`;
                    textInput.name = `options[${index}][text]`;
                    radioInput.value = index;
                    radioInput.id = `correct_${index + 1}`;
                    radioLabel.setAttribute('for', `correct_${index + 1}`);

                    if (removeButton) {
                        removeButton.setAttribute('onclick', `removeOption(${index + 1})`);
                    }
                });
            }

            document.getElementById('questionForm').addEventListener('submit', function(e) {
                const correctRadio = document.querySelector('input[name="correct_option"]:checked');
                if (!correctRadio) {
                    e.preventDefault();
                    alert('Silakan pilih jawaban yang benar!');
                    return;
                }

                const textInputs = document.querySelectorAll('input[name*="[text]"]');
                let hasEmptyOption = false;
                textInputs.forEach(input => {
                    if (!input.value.trim()) {
                        hasEmptyOption = true;
                    }
                });

                if (hasEmptyOption) {
                    e.preventDefault();
                    alert('Semua pilihan jawaban harus diisi!');
                    return;
                }
            });
        });
    </script>
@endpush
