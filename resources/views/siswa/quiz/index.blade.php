<h1>List Quiz</h1>
<a href="{{ route('quizzes.create') }}">+ Create Quiz</a>
<ul>
    @foreach ($quizzes as $quiz)
        <li>
            {{ $quiz->title }}
            <a href="{{ route('quiz.attempt', $quiz) }}">Attempt</a>
        </li>
    @endforeach
</ul>
