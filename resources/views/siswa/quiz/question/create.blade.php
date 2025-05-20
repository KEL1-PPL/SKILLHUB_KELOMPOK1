@extends('layouts.app')

@section('content')
    <h1>Tambah Soal untuk Kuis: {{ $quiz->title }}</h1>

    <form method="POST" action="{{ route('questions.store', $quiz) }}">
        @csrf

        <div>
            <label>Pertanyaan:</label><br>
            <textarea name="question_text" rows="3" required>{{ old('question_text') }}</textarea>
        </div>

        <div>
            <label>Pilihan Jawaban:</label><br>
            @for ($i = 0; $i < 4; $i++)
                <input type="text" name="options[{{ $i }}][option_text]" placeholder="Pilihan {{ $i + 1 }}" required>
                <label>
                    <input type="radio" name="correct_option" value="{{ $i }}" required>
                    Jawaban Benar
                </label>
                <br>
            @endfor
        </div>

        <button type="submit">Simpan Soal</button>
    </form>

    <br>
    <a href="{{ route('quizzes.index') }}">← Kembali ke Daftar Kuis</a>
@endsection
