@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg p-6">
    <h1 class="text-2xl font-bold mb-6">Tambah Soal untuk Kuis: <span class="text-blue-600">{{ $quiz->title }}</span></h1>

    {{-- Flash Error Message --}}
    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-2 rounded mb-4">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('questions.store', $quiz) }}">
        @csrf

        {{-- Pertanyaan --}}
        <div class="mb-6">
            <label for="question_text" class="block font-semibold mb-2">Pertanyaan:</label>
            <textarea id="question_text" name="question_text" rows="3"
                      class="w-full border rounded px-4 py-2 @error('question_text') border-red-500 @enderror"
                      required>{{ old('question_text') }}</textarea>
            @error('question_text')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Pilihan Jawaban --}}
        <div class="mb-6">
            <label class="block font-semibold mb-3">Pilihan Jawaban:</label>

            @for ($i = 0; $i < 4; $i++)
                <div class="mb-3 flex items-center gap-3">
                    <input type="text" name="options[{{ $i }}][option_text]"
                           value="{{ old("options.$i.option_text") }}"
                           placeholder="Pilihan {{ $i + 1 }}"
                           class="flex-1 border rounded px-4 py-2 @error("options.$i.option_text") border-red-500 @enderror"
                           required>

                    <label class="flex items-center gap-1 text-sm">
                        <input type="radio" name="correct_option" value="{{ $i }}"
                               {{ old('correct_option') == $i ? 'checked' : '' }} required>
                        Jawaban Benar
                    </label>
                </div>

                @error("options.$i.option_text")
                    <p class="text-red-500 text-sm mb-2">{{ $message }}</p>
                @enderror
            @endfor

            @error('correct_option')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        {{-- Submit --}}
        <div class="flex justify-between items-center">
            <a href="{{ route('questions.index', $quiz) }}" class="text-sm text-blue-600 hover:underline">
                ← Kembali ke Daftar Soal
            </a>
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded">
                Simpan Soal
            </button>
        </div>
    </form>
</div>
@endsection
