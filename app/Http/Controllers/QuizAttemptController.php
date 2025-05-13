<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Option;
use App\Models\Answer;
use Illuminate\Http\Request;

class QuizAttemptController extends Controller
{
    public function show(Quiz $quiz)
    {
        $questions = $quiz->questions()->with('options')->get();
        return view('quiz.attempt', compact('quiz', 'questions'));
    }

    public function submit(Request $request, Quiz $quiz)
    {
        $score = 0;
        foreach ($request->answers as $question_id => $option_id) {
            $option = Option::find($option_id);
            Answer::create([
                'user_id' => auth()->id(),
                'question_id' => $question_id,
                'option_id' => $option_id
            ]);
            if ($option && $option->is_correct) {
                $score++;
            }
        }
        return view('quiz.result', compact('score'));
    }
}
