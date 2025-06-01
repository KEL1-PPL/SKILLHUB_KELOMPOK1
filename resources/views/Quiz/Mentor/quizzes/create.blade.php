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

        .form-control, .form-select {
            border: 2px solid #D4D4CE;
            border-radius: 8px;
            font-family: 'Figtree', sans-serif;
        }

        .form-control:focus, .form-select:focus {
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

        .form-label {
            color: #023246;
            font-weight: 600;
        }

        .alert {
            border-radius: 8px;
        }
    </style>
@endpush

@section('content')
    <div class="body-wrapper">
        <div class="container mt-5 pt-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-transparent border-bottom-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h2>📝 Tambah Quiz Baru</h2>
                                <a href="{{ route('mentor.quizzes.index') }}" class="btn btn-secondary">
                                    ← Kembali
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('mentor.quizzes.store') }}" method="POST">
                                @csrf

                                <!-- Course Selection -->
                                <div class="mb-3">
                                    <label for="course_id" class="form-label">Kursus *</label>
                                    <select name="course_id" id="course_id" class="form-select @error('course_id') is-invalid @enderror" required>
                                        <option value="">Pilih Kursus</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                                {{ $course->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('course_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Material Selection (Dynamic) -->
                                <div class="mb-3">
                                    <label for="material_id" class="form-label">Materi (Opsional)</label>
                                    <select name="material_id" id="material_id" class="form-select @error('material_id') is-invalid @enderror">
                                        <option value="">Pilih Materi (Opsional)</option>
                                    </select>
                                    @error('material_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Quiz Title -->
                                <div class="mb-3">
                                    <label for="title" class="form-label">Judul Quiz *</label>
                                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
                                           value="{{ old('title') }}" required maxlength="255">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="mb-3">
                                    <label for="description" class="form-label">Deskripsi</label>
                                    <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Quiz Settings Row -->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="time_limit" class="form-label">Batas Waktu (Menit)</label>
                                            <input type="number" name="time_limit" id="time_limit" class="form-control @error('time_limit') is-invalid @enderror"
                                                   value="{{ old('time_limit') }}" min="1" placeholder="Tidak terbatas">
                                            @error('time_limit')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="max_attempts" class="form-label">Maks Percobaan *</label>
                                            <input type="number" name="max_attempts" id="max_attempts" class="form-control @error('max_attempts') is-invalid @enderror"
                                                   value="{{ old('max_attempts', 3) }}" min="1" required>
                                            @error('max_attempts')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="passing_score" class="form-label">Nilai Lulus (%) *</label>
                                            <input type="number" name="passing_score" id="passing_score" class="form-control @error('passing_score') is-invalid @enderror"
                                                   value="{{ old('passing_score', 70) }}" min="0" max="100" required>
                                            @error('passing_score')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Buttons -->
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('mentor.quizzes.index') }}" class="btn btn-secondary">Batal</a>
                                    <button type="submit" class="btn btn-primary">Simpan Quiz</button>
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
            const courseSelect = document.getElementById('course_id');
            const materialSelect = document.getElementById('material_id');

            courseSelect.addEventListener('change', function() {
                const courseId = this.value;
                materialSelect.innerHTML = '<option value="">Pilih Materi (Opsional)</option>';

                if (courseId) {
                    fetch(`/mentor/courses/${courseId}/materials`)
                        .then(response => response.json())
                        .then(materials => {
                            materials.forEach(material => {
                                const option = document.createElement('option');
                                option.value = material.id;
                                option.textContent = material.title;
                                materialSelect.appendChild(option);
                            });
                        })
                        .catch(error => {
                            console.error('Error fetching materials:', error);
                        });
                }
            });

            if (courseSelect.value) {
                courseSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endpush
