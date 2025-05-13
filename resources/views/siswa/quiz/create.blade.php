<h1>Create Quiz</h1>
<form method="POST" action="{{ route('quizzes.store') }}">
    @csrf
    <input type="text" name="title" placeholder="Quiz Title" required>
    <textarea name="description" placeholder="Description"></textarea>
    <button type="submit">Save</button>
</form>
