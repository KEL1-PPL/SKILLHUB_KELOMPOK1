<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Option;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuizAttemptController extends Controller
{
    /**
     * Tampilkan halaman untuk mengerjakan kuis.
     */
    public function show(Quiz $quiz)
    {
        $questions = $quiz->questions()->with('options')->get();
        return view('quiz.attempt', compact('quiz', 'questions'));
    }

    /**
     * Proses pengiriman jawaban kuis.
     */
    public function submit(Request $request, Quiz $quiz)
    {
        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|integer|exists:options,id',
        ]);

        $score = 0;
        $total = $quiz->questions()->count();
        $answers = [];

        try {
            DB::beginTransaction();

            foreach ($request->answers as $question_id => $option_id) {
                $option = Option::find($option_id);

                // Simpan jawaban user
                $answers[] = Answer::create([
                    'user_id' => auth()->id(),
                    'question_id' => $question_id,
                    'option_id' => $option_id,
                ]);

                if ($option && $option->is_correct) {
                    $score++;
                }
            }

            DB::commit();

            return view('quiz.result', [
                'quiz' => $quiz,
                'score' => $score,
                'total' => $total,
                'answers' => $answers,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan jawaban kuis: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memproses kuis.');
        }
    }
}
