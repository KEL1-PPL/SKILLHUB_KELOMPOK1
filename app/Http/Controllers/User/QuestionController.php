<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuestionController extends Controller
{
    public function index(Quiz $quiz)
    {
        $quiz->load('questions.options');
        return view('quiz.question.list', compact('quiz'));
    }

    public function create(Quiz $quiz)
    {
        return view('quiz.question.create', compact('quiz'));
    }

    public function store(Request $request, Quiz $quiz)
    {
        $request->validate([
            'question_text' => 'required|string',
            'options' => 'required|array|min:2',
            'options.*.option_text' => 'required|string',
            'correct_option' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $question = $quiz->questions()->create([
                'question_text' => $request->question_text,
            ]);

            foreach ($request->options as $index => $option) {
                $question->options()->create([
                    'option_text' => $option['option_text'],
                    'is_correct' => $index == $request->correct_option,
                ]);
            }

            DB::commit();
            return redirect()->route('questions.index', $quiz)->with('success', 'Soal berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menambahkan soal: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambahkan soal.');
        }
    }

    public function edit(Question $question)
    {
        $question->load('options');
        return view('quiz.question.edit', compact('question'));
    }

    public function update(Request $request, Question $question)
    {
        $request->validate([
            'question_text' => 'required|string',
            'options' => 'required|array|min:2',
            'options.*.option_text' => 'required|string',
            'correct_option' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $question->update([
                'question_text' => $request->question_text,
            ]);

            $question->options()->delete();

            foreach ($request->options as $index => $option) {
                $question->options()->create([
                    'option_text' => $option['option_text'],
                    'is_correct' => $index == $request->correct_option,
                ]);
            }

            DB::commit();
            return redirect()->route('questions.index', $question->quiz_id)->with('success', 'Soal berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat memperbarui soal: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui soal.');
        }
    }

    public function destroy(Question $question)
    {
        try {
            $quizId = $question->quiz_id;

            DB::transaction(function () use ($question) {
                $question->options()->delete();
                $question->delete();
            });

            return redirect()->route('questions.index', $quizId)->with('success', 'Soal berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error saat menghapus soal: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus soal.');
        }
    }

    public function preview(Request $request)
    {
        $request->validate([
            'question_text' => 'required|string',
            'options' => 'required|array|min:2',
            'options.*.option_text' => 'required|string',
            'correct_option' => 'required|integer|min:0',
        ]);

        $question = [
            'question_text' => $request->question_text,
            'options' => collect($request->options)->map(function ($option, $index) use ($request) {
                return [
                    'option_text' => $option['option_text'],
                    'is_correct' => $index == $request->correct_option,
                ];
            }),
        ];

        return view('quiz.question.preview', compact('question'));
    }

    public function apiIndex(Quiz $quiz)
    {
        return response()->json($quiz->load('questions.options'));
    }
}
