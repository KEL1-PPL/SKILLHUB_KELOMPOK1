@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Hasil Kuis</h2>
    <p class="mb-4">Skor Anda: <span class="font-semibold">{{ $score }} / {{ $questions->count() }}</span></p>

    @foreach ($questions as $index => $question)
        @php
            $userAnswer = $answers[$question->id] ?? null;
            $selectedOptionId = $userAnswer->option_id ?? null;
        @endphp

        <div class="mb-6">
            <h4 class="font-semibold">{{ $index + 1 }}. {{ $question->question_text }}</h4>

            <ul class="mt-2 space-y-1">
                @foreach ($question->options as $option)
                    <li class="
                        @if ($option->is_correct)
                            text-green-600 font-semibold
                        @elseif ($option->id == $selectedOptionId)
                            text-red-500
                        @endif
                    ">
                        {{ $option->option_text }}

                        @if ($option->is_correct)
                            <span class="text-xs text-green-700 ml-2">(Benar)</span>
                        @endif

                        @if ($option->id == $selectedOptionId && !$option->is_correct)
                            <span class="text-xs text-red-700 ml-2">(Jawaban Anda)</span>
                        @endif
                    </li>
                @endforeach
            </ul>

            {{-- Penjelasan --}}
            @if ($question->explanation)
                <p class="text-sm text-gray-600 mt-2 italic">Penjelasan: {{ $question->explanation }}</p>
            @endif
        </div>
    @endforeach
</div>
@endsection
