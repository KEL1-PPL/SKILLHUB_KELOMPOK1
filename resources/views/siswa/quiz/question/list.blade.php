@extends('layouts.app')

@section('content')
    <h1>Daftar Soal untuk Kuis: {{ $quiz->title }}</h1>

    <a href="{{ route('questions.create', $quiz) }}">+ Tambah Soal Baru</a>
    <ul>
        @foreach ($quiz->questions as $question)
            <li>
                <strong>{{ $question->question_text }}</strong>
                <ul>
                    @foreach ($question->options as $option)
                        <li>
                            {{ $option->option_text }} 
                            @if ($option->is_correct)
                                <strong>(Benar)</strong>
                            @endif
                        </li>
                    @endforeach
                </ul>
                <a href="{{ route('questions.edit', $question) }}">Edit</a>
                <form method="POST" action="{{ route('questions.destroy', $question) }}" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Yakin ingin menghapus soal ini?')">Hapus</button>
                </form>
            </li>
        @endforeach
    </ul>

    <br>
    <a href="{{ route('quizzes.index') }}">← Kembali ke Daftar Kuis</a>
@endsection
