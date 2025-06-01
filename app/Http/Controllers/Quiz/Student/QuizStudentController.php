<?php

namespace App\Http\Controllers\Quiz\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizStudentController extends Controller
{
    public function index()
    {
        $enrolledCourseIds = auth()->user()->courseEnrollments()->pluck('course_id');
        $title = 'index quiz';
        $quizzes = Quiz::with(['course', 'material'])
            ->whereIn('course_id', $enrolledCourseIds)
            ->where('is_active', true)
            ->paginate(10);

        return view('Quiz.Student.quizzes.index', compact('quizzes', 'title'));
    }

    public function show(Quiz $quiz)
    {
        $title = 'show quiz';
        $isEnrolled = auth()->user()->courseEnrollments()
            ->where('course_id', $quiz->course_id)
            ->exists();

        if (!$isEnrolled) {
            abort(403, 'Anda belum terdaftar di kursus ini.');
        }

        if ($quiz->material_id) {
            $materialCompleted = auth()->user()->materialCompletions()
                ->where('material_id', $quiz->material_id)
                ->where('is_completed', true)
                ->exists();

            if (!$materialCompleted) {
                return redirect()->back()
                    ->with('error', 'Anda harus menyelesaikan materi terkait terlebih dahulu.');
            }
        }

        $attempts = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('user_id', auth()->id())
            ->with(['answers.question', 'answers.selectedOption'])
            ->orderBy('attempt_number', 'desc')
            ->get();

        foreach ($attempts as $attempt) {
            $attempt->total_questions = $quiz->questions()->count();
            $attempt->correct_answers = $attempt->answers()->where('is_correct', true)->count();
            $attempt->is_passed = $attempt->score >= $quiz->passing_score;
        }

        $canTakeQuiz = $attempts->count() < $quiz->max_attempts;
        $bestScore = $attempts->max('score') ?? 0;

        return view('Quiz.Student.quizzes.show', compact('quiz', 'attempts', 'canTakeQuiz', 'title', 'bestScore'));
    }

    public function start(Quiz $quiz)
    {
        $isEnrolled = auth()->user()->courseEnrollments()
            ->where('course_id', $quiz->course_id)
            ->exists();

        if (!$isEnrolled) {
            abort(403);
        }

        $userAttempts = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('user_id', auth()->id())
            ->count();

        if ($userAttempts >= $quiz->max_attempts) {
            return redirect()->route('student.quizzes.show', $quiz)
                ->with('error', 'Anda telah mencapai batas maksimal percobaan.');
        }

        $activeAttempt = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('user_id', auth()->id())
            ->where('status', 'in_progress')
            ->first();

        if ($activeAttempt) {
            return redirect()->route('student.quizzes.take', [$quiz, $activeAttempt]);
        }

        $totalPoints = $quiz->questions()->sum('points');

        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'user_id' => auth()->id(),
            'attempt_number' => $userAttempts + 1,
            'started_at' => now(),
            'status' => 'in_progress',
            'total_points' => $totalPoints,
            'earned_points' => 0,
            'score' => 0
        ]);

        return redirect()->route('student.quizzes.take', [$quiz, $attempt]);
    }

    public function take(Quiz $quiz, QuizAttempt $attempt)
    {
        $title = "take quiz";

        if ($attempt->user_id !== auth()->id()) {
            abort(403);
        }

        if ($attempt->status === 'completed') {
            return redirect()->route('student.quizzes.result', [$quiz, $attempt]);
        }

        if ($quiz->time_limit && $attempt->isExpired()) {
            $this->autoSubmitQuiz($attempt);
            return redirect()->route('student.quizzes.result', [$quiz, $attempt])
                ->with('warning', 'Waktu habis! Quiz otomatis diserahkan.');
        }

        $questions = $quiz->questions()->with('options')->orderBy('order')->get();

        $existingAnswers = [];
        foreach ($attempt->answers as $answer) {
            if ($answer->essay_answer) {
                $existingAnswers[$answer->question_id] = $answer->essay_answer;
            } else {
                $existingAnswers[$answer->question_id] = $answer->selected_option_id;
            }
        }

        return view('Quiz.Student.quizzes.take', compact('quiz', 'attempt', 'questions', 'existingAnswers', 'title'));
    }

    public function saveAnswer(Request $request, Quiz $quiz, QuizAttempt $attempt)
    {
        if ($attempt->user_id !== auth()->id() || $attempt->status !== 'in_progress') {
            return response()->json(['error' => 'Invalid attempt'], 403);
        }

        if ($quiz->time_limit && $attempt->isExpired()) {
            return response()->json(['error' => 'Time limit exceeded'], 403);
        }

        $validated = $request->validate([
            'question_id' => 'required|exists:quiz_questions,id',
            'selected_option_id' => 'nullable|exists:quiz_question_options,id',
            'essay_answer' => 'nullable|string'
        ]);

        $question = $quiz->questions()->findOrFail($validated['question_id']);

        $answer = QuizAnswer::updateOrCreate(
            [
                'attempt_id' => $attempt->id,
                'question_id' => $question->id
            ],
            [
                'selected_option_id' => $validated['selected_option_id'],
                'essay_answer' => $validated['essay_answer']
            ]
        );

        if ($question->type !== 'essay' && $validated['selected_option_id']) {
            $selectedOption = $question->options()->find($validated['selected_option_id']);
            $answer->update([
                'is_correct' => $selectedOption->is_correct,
                'points_earned' => $selectedOption->is_correct ? $question->points : 0
            ]);
        } else {
            $answer->update([
                'is_correct' => null,
                'points_earned' => 0
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Jawaban tersimpan']);
    }

    public function submit(Quiz $quiz, QuizAttempt $attempt)
    {
        if ($attempt->user_id !== auth()->id() || $attempt->status !== 'in_progress') {
            abort(403);
        }

        DB::transaction(function () use ($attempt, $quiz) {
            $this->calculateScore($attempt);

            $attempt->update([
                'status' => 'completed',
                'submitted_at' => now(),
                'time_taken' => $attempt->started_at ? now()->diffInSeconds($attempt->started_at) : 0
            ]);
        });

        return redirect()->route('student.quizzes.result', [$quiz, $attempt])
            ->with('success', 'Quiz berhasil diserahkan!');
    }

    public function result(Quiz $quiz, QuizAttempt $attempt)
    {
        $title = "result quiz";

        if ($attempt->user_id !== auth()->id()) {
            abort(403);
        }

        $attempt->load(['answers.question.options', 'answers.selectedOption']);
        $attempt->total_questions = $quiz->questions()->count();
        $attempt->correct_answers = $attempt->answers()->where('is_correct', true)->count();
        $attempt->is_passed = $attempt->score >= $quiz->passing_score;


        return view('Quiz.Student.quizzes.result', compact('quiz', 'attempt', 'title'));
    }

    private function calculateScore(QuizAttempt $attempt)
    {
        $totalPoints = 0;
        $earnedPoints = 0;
        $correctAnswers = 0;

        foreach ($attempt->quiz->questions as $question) {
            $totalPoints += $question->points;

            $answer = $attempt->answers()->where('question_id', $question->id)->first();

            if ($answer) {
                $earnedPoints += $answer->points_earned ?? 0;

                if ($answer->is_correct) {
                    $correctAnswers++;
                }
            }
        }

        $score = $totalPoints > 0 ? ($earnedPoints / $totalPoints) * 100 : 0;

        $attempt->update([
            'total_points' => $totalPoints,
            'earned_points' => $earnedPoints,
            'score' => round($score, 2)
        ]);
    }

    private function autoSubmitQuiz(QuizAttempt $attempt)
    {
        $this->calculateScore($attempt);

        $attempt->update([
            'status' => 'completed',
            'submitted_at' => now(),
            'time_taken' => $attempt->started_at ? now()->diffInSeconds($attempt->started_at) : 0
        ]);
    }

    public function certificate(QuizAttempt $attempt)
    {
        if ($attempt->user_id !== auth()->id()) {
            abort(403);
        }

        $isPassed = $attempt->score >= $attempt->quiz->passing_score;

        if (!$isPassed) {
            abort(403, 'Sertifikat hanya tersedia untuk peserta yang lulus.');
        }

        return view('Quiz.Student.quizzes.certificate', [
            'attempt' => $attempt,
            'quiz' => $attempt->quiz,
            'user' => $attempt->user,
        ]);
    }
}
