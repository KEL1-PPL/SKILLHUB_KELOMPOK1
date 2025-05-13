<h1>{{ $quiz->title }}</h1>
<form method="POST" action="{{ route('quiz.submit', $quiz) }}">
    @csrf
    @foreach($questions as $question)
        <p><strong>{{ $question->question_text }}</strong></p>
        @foreach($question->options as $option)
            <label>
                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}" required>
                {{ $option->option_text }}
            </label><br>
        @endforeach
    @endforeach
    <button type="submit">Submit Quiz</button>
</form>
