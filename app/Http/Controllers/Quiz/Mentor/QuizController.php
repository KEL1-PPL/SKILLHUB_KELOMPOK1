<?php

namespace App\Http\Controllers\Quiz\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Material;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "index quiz";
        $quizzes = Quiz::with(['course', 'material'])
            ->withCount(['questions', 'quizAttempts'])
            ->where('created_by', auth()->id())
            ->paginate(10);

        return view('Quiz.Mentor.quizzes.index', compact('quizzes', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "tambah quiz";
        $courses = Course::where('created_by', auth()->id())->get();
        return view('Quiz.Mentor.quizzes.create', compact('courses', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'material_id' => 'nullable|exists:materials,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'time_limit' => 'nullable|integer|min:1',
            'max_attempts' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
        ]);

        $validated['created_by'] = auth()->id();

        $quiz = Quiz::create($validated);

        return redirect()->route('mentor.quizzes.show', $quiz)
            ->with('success', 'Quiz berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Quiz $quiz)
    {
        $this->authorize('view', $quiz);
        $title = "show quiz";
        $quiz->load(['questions.options', 'course', 'material']);

        return view('Quiz.Mentor.quizzes.show', compact('quiz', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quiz $quiz)
    {
        $this->authorize('update', $quiz);
        $title = "edit quiz";
        $courses = Course::where('created_by', auth()->id())->get();
        $materials = Material::where('course_id', $quiz->course_id)->get();

        return view('Quiz.Mentor.quizzes.edit', compact('quiz', 'courses', 'materials', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quiz $quiz)
    {
        $this->authorize('update', $quiz);

        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'material_id' => 'nullable|exists:materials,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'time_limit' => 'nullable|integer|min:1',
            'max_attempts' => 'required|integer|min:1',
            'passing_score' => 'required|integer|min:0|max:100',
            'is_active' => 'boolean'
        ]);

        $quiz->update($validated);

        return redirect()->route('mentor.quizzes.show', $quiz)
            ->with('success', 'Quiz berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $quiz)
    {
        $this->authorize('delete', $quiz);

        $quiz->delete();

        return redirect()->route('mentor.quizzes.index')
            ->with('success', 'Quiz berhasil dihapus!');
    }

    /**
     * Analyze quiz results and student performance
     */
    public function analyze(Quiz $quiz)
    {
        $this->authorize('view', $quiz);

        $title = "Analisis Quiz - " . $quiz->title;
        $quiz->load(['course', 'material', 'questions.options']);

        $attempts = QuizAttempt::with(['user', 'answers.question', 'answers.selectedOption'])
            ->where('quiz_id', $quiz->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalAttempts = $attempts->count();
        $uniqueStudents = $attempts->pluck('user_id')->unique()->count();
        $completedAttempts = $attempts->where('status', 'completed');
        $passedAttempts = $completedAttempts->where('score', '>=', $quiz->passing_score);

        $stats = [
            'total_attempts' => $totalAttempts,
            'unique_students' => $uniqueStudents,
            'completion_rate' => $totalAttempts > 0 ? round(($completedAttempts->count() / $totalAttempts) * 100, 1) : 0,
            'pass_rate' => $completedAttempts->count() > 0 ? round(($passedAttempts->count() / $completedAttempts->count()) * 100, 1) : 0,
            'average_score' => $completedAttempts->count() > 0 ? round($completedAttempts->avg('score'), 1) : 0,
            'highest_score' => $completedAttempts->count() > 0 ? $completedAttempts->max('score') : 0,
            'lowest_score' => $completedAttempts->count() > 0 ? $completedAttempts->min('score') : 0,
            'average_duration' => $completedAttempts->count() > 0 ? $this->calculateAverageDuration($completedAttempts) : '0 menit'
        ];

        $scoreRanges = [
            '90-100' => $completedAttempts->whereBetween('score', [90, 100])->count(),
            '80-89' => $completedAttempts->whereBetween('score', [80, 89])->count(),
            '70-79' => $completedAttempts->whereBetween('score', [70, 79])->count(),
            '60-69' => $completedAttempts->whereBetween('score', [60, 69])->count(),
            '0-59' => $completedAttempts->where('score', '<', 60)->count(),
        ];

        $questionAnalysis = $this->analyzeQuestions($quiz, $completedAttempts);
        $studentPerformance = $this->analyzeStudentPerformance($attempts);
        $timeAnalysis = $this->analyzeAttemptsByTime($attempts);

        return view('Quiz.Mentor.quizzes.analyze', compact(
            'quiz',
            'title',
            'stats',
            'scoreRanges',
            'questionAnalysis',
            'studentPerformance',
            'timeAnalysis',
            'attempts'
        ));
    }

    public function getMaterials(Course $course)
    {
        $materials = $course->materials()->select('id', 'title')->get();
        return response()->json($materials);
    }

    /**
     * Calculate average duration from attempts
     */
    private function calculateAverageDuration($attempts)
    {
        $totalMinutes = 0;
        $count = 0;

        foreach ($attempts as $attempt) {
            if ($attempt->started_at && $attempt->submitted_at) {
                $duration = $attempt->started_at->diffInMinutes($attempt->submitted_at);
                $totalMinutes += $duration;
                $count++;
            }
        }

        if ($count == 0) return '0 menit';

        $avgMinutes = round($totalMinutes / $count);
        return $avgMinutes . ' menit';
    }

    /**
     * Analyze question performance
     */
    private function analyzeQuestions($quiz, $attempts)
    {
        $questionStats = [];

        foreach ($quiz->questions as $question) {
            $totalAnswers = 0;
            $correctAnswers = 0;
            $commonWrongAnswers = [];

            foreach ($attempts as $attempt) {
                $answer = $attempt->answers->where('question_id', $question->id)->first();
                if ($answer) {
                    $totalAnswers++;
                    if ($answer->is_correct) {
                        $correctAnswers++;
                    } else {
                        if ($question->type === 'multiple_choice' && $answer->selectedOption) {
                            $optionText = $answer->selectedOption->option_text;
                            $commonWrongAnswers[$optionText] = ($commonWrongAnswers[$optionText] ?? 0) + 1;
                        }
                    }
                }
            }

            $questionStats[] = [
                'question' => $question,
                'total_answers' => $totalAnswers,
                'correct_answers' => $correctAnswers,
                'accuracy_rate' => $totalAnswers > 0 ? round(($correctAnswers / $totalAnswers) * 100, 1) : 0,
                'difficulty_level' => $this->getDifficultyLevel($totalAnswers > 0 ? ($correctAnswers / $totalAnswers) * 100 : 0),
                'common_wrong_answers' => $commonWrongAnswers
            ];
        }

        return collect($questionStats)->sortBy('accuracy_rate');
    }

    /**
     * Analyze student performance
     */
    private function analyzeStudentPerformance($attempts)
    {
        $studentStats = [];

        $groupedAttempts = $attempts->groupBy('user_id');

        foreach ($groupedAttempts as $userId => $userAttempts) {
            $user = $userAttempts->first()->user;
            $completedAttempts = $userAttempts->where('status', 'completed');

            if ($completedAttempts->count() > 0) {
                $bestScore = $completedAttempts->max('score');
                $avgScore = round($completedAttempts->avg('score'), 1);
                $improvementTrend = $this->calculateImprovementTrend($completedAttempts);

                $studentStats[] = [
                    'user' => $user,
                    'total_attempts' => $userAttempts->count(),
                    'completed_attempts' => $completedAttempts->count(),
                    'best_score' => $bestScore,
                    'average_score' => $avgScore,
                    'improvement_trend' => $improvementTrend,
                    'last_attempt' => $userAttempts->sortByDesc('created_at')->first()->created_at
                ];
            }
        }

        return collect($studentStats)->sortByDesc('best_score');
    }

    /**
     * Analyze attempts by time periods
     */
    private function analyzeAttemptsByTime($attempts)
    {
        $timeData = [
            'daily' => [],
            'hourly' => []
        ];

        $dailyAttempts = $attempts->groupBy(function ($attempt) {
            return $attempt->created_at->format('Y-m-d');
        });

        foreach ($dailyAttempts as $date => $dayAttempts) {
            $timeData['daily'][] = [
                'date' => $date,
                'attempts' => $dayAttempts->count(),
                'average_score' => round($dayAttempts->where('status', 'completed')->avg('score') ?? 0, 1)
            ];
        }

        $hourlyAttempts = $attempts->groupBy(function ($attempt) {
            return $attempt->created_at->format('H');
        });

        for ($hour = 0; $hour < 24; $hour++) {
            $hourStr = sprintf('%02d', $hour);
            $timeData['hourly'][] = [
                'hour' => $hourStr . ':00',
                'attempts' => $hourlyAttempts->get($hourStr, collect())->count()
            ];
        }

        return $timeData;
    }

    /**
     * Get difficulty level based on accuracy rate
     */
    private function getDifficultyLevel($accuracyRate)
    {
        if ($accuracyRate >= 80) return 'Mudah';
        if ($accuracyRate >= 60) return 'Sedang';
        if ($accuracyRate >= 40) return 'Sulit';
        return 'Sangat Sulit';
    }

    /**
     * Calculate improvement trend for a student
     */
    private function calculateImprovementTrend($attempts)
    {
        $sortedAttempts = $attempts->sortBy('created_at')->values();

        if ($sortedAttempts->count() < 2) {
            return 'Tidak cukup data';
        }

        $firstScore = $sortedAttempts->first()->score;
        $lastScore = $sortedAttempts->last()->score;

        $improvement = $lastScore - $firstScore;

        if ($improvement > 10) return 'Meningkat Signifikan';
        if ($improvement > 0) return 'Meningkat';
        if ($improvement == 0) return 'Stabil';
        if ($improvement > -10) return 'Menurun';
        return 'Menurun Signifikan';
    }
}
