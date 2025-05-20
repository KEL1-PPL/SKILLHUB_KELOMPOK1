<div>
    <label>Pertanyaan:</label><br>
    <input type="text" name="question_text" value="{{ old('question_text', $question->question_text ?? '') }}" required><br><br>

    <label>Pilihan Jawaban:</label><br>
    @php
        $options = old('options', $question ? $question->options->toArray() : []);
    @endphp

    @for ($i = 0; $i < 4; $i++)
        <input type="text" name="options[{{ $i }}][option_text]"
               value="{{ $options[$i]['option_text'] ?? '' }}"
               placeholder="Pilihan {{ $i + 1 }}" required>

        <label>
            <input type="radio" name="correct_option" value="{{ $i }}"
                   {{ (old('correct_option') == $i || (!old('correct_option') && isset($options[$i]['is_correct']) && $options[$i]['is_correct'])) ? 'checked' : '' }}>
            Jawaban Benar
        </label>
        <br>
    @endfor
</div>
