<div class="card p-4 rounded-lg shadow-md bg-white max-w-2xl mx-auto">
    <h2 class="text-xl font-bold mb-4">Form Soal</h2>

    {{-- Pertanyaan --}}
    <div class="mb-4">
        <label for="question_text" class="block font-semibold mb-1">Pertanyaan:</label>
        <input type="text" id="question_text" name="question_text"
               class="w-full border rounded px-3 py-2 @error('question_text') border-red-500 @enderror"
               value="{{ old('question_text', $question->question_text ?? '') }}" required>

        @error('question_text')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Pilihan Jawaban --}}
    <div>
        <label class="block font-semibold mb-2">Pilihan Jawaban:</label>

        @php
            $options = old('options', $question ? $question->options->toArray() : []);
            $correct = old('correct_option', collect($options)->search(fn($o) => $o['is_correct'] ?? false));
        @endphp

        @for ($i = 0; $i < 4; $i++)
            <div class="mb-3 flex items-center gap-3">
                <input type="text"
                       name="options[{{ $i }}][option_text]"
                       class="flex-1 border rounded px-3 py-2 @error("options.$i.option_text") border-red-500 @enderror"
                       value="{{ $options[$i]['option_text'] ?? '' }}"
                       placeholder="Pilihan {{ $i + 1 }}" required>

                <label class="flex items-center gap-1 text-sm">
                    <input type="radio" name="correct_option" value="{{ $i }}"
                           {{ $correct == $i ? 'checked' : '' }}>
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
</div>
