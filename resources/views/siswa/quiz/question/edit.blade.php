@extends('layouts.app')

@section('content')
    <h1>Edit Soal</h1>

    <form method="POST" action="{{ route('questions.update', $question) }}">
        @csrf
        @method('PUT')

        <div>
            <label>Pertanyaan:</label><br>
            <textarea name="question_text" rows="3" required>{{ old('question_text', $question->question_text) }}</textarea>
        </div>

        <div>
            <label>Pilihan Jawaban:</label><br>
            @foreach ($question->options as $index => $option)
                <input type="text" name="options[{{ $index }}][option_text]" value="{{ $option->option_text }}" required>
                <label>
                    <input type="radio" name="correct_option" value="{{ $index }}" 
                        {{ $option->is_correct ? 'checked' : '' }}>
                    Jawaban Benar
                </label>
                <br>
            @endforeach
        </div>

        <button type="submit">Perbarui Soal</button>
    </form>

    <br>
    <a href="{{ route('quizzes.index') }}">← Kembali ke Daftar Kuis</a>
@endsection
