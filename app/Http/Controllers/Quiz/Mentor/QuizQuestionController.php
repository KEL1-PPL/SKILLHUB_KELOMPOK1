<?php

namespace App\Http\Controllers\Quiz\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizQuestionController extends Controller
{

    /**
     * Show the form for creating a new resource.
     */
    public function create(Quiz $quiz)
    {
        $this->authorize('update', $quiz);
        $title = "tambah quiz";
        return view('Quiz.Mentor.quiz-questions.create', compact('quiz', 'title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Quiz $quiz)
    {
        $this->authorize('update', $quiz);

        $validated = $request->validate([
            'question' => 'required|string|max:1000',
            'points' => 'required|integer|min:1|max:100',
            'options' => 'required|array|min:2|max:6',
            'options.*.text' => 'required|string|max:255',
            'correct_option' => 'required|integer|min:0',
        ], [
            'question.required' => 'Pertanyaan wajib diisi.',
            'question.max' => 'Pertanyaan maksimal 1000 karakter.',
            'points.required' => 'Poin wajib diisi.',
            'points.min' => 'Poin minimal 1.',
            'points.max' => 'Poin maksimal 100.',
            'options.required' => 'Pilihan jawaban wajib diisi.',
            'options.min' => 'Minimal harus ada 2 pilihan jawaban.',
            'options.max' => 'Maksimal 6 pilihan jawaban.',
            'options.*.text.required' => 'Teks pilihan jawaban wajib diisi.',
            'options.*.text.max' => 'Teks pilihan jawaban maksimal 255 karakter.',
            'correct_option.required' => 'Harus memilih jawaban yang benar.',
        ]);

        DB::transaction(function () use ($validated, $quiz) {
            $question = $quiz->questions()->create([
                'question' => $validated['question'],
                'type' => 'multiple_choice',
                'points' => $validated['points'],
                'order' => $quiz->questions()->count() + 1
            ]);

            foreach ($validated['options'] as $index => $option) {
                $isCorrect = ($index == $validated['correct_option']);

                $question->options()->create([
                    'option_text' => $option['text'],
                    'is_correct' => $isCorrect,
                    'order' => $index + 1
                ]);
            }
        });

        return redirect()->route('mentor.quizzes.show', $quiz)
            ->with('success', 'Soal berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Quiz $quiz, QuizQuestion $question)
    {
        $title = "edit quiz";
        $this->authorize('update', $quiz);
        return view('Quiz.Mentor.quiz-questions.edit', compact('quiz', 'question', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Quiz $quiz, QuizQuestion $question)
    {
        $this->authorize('update', $quiz);

        $validated = $request->validate([
            'question' => 'required|string|max:1000',
            'points' => 'required|integer|min:1|max:100',
            'options' => 'required|array|min:2|max:6',
            'options.*.text' => 'required|string|max:255',
            'correct_option' => 'required|integer|min:0',
        ], [
            'question.required' => 'Pertanyaan wajib diisi.',
            'question.max' => 'Pertanyaan maksimal 1000 karakter.',
            'points.required' => 'Poin wajib diisi.',
            'points.min' => 'Poin minimal 1.',
            'points.max' => 'Poin maksimal 100.',
            'options.required' => 'Pilihan jawaban wajib diisi.',
            'options.min' => 'Minimal harus ada 2 pilihan jawaban.',
            'options.max' => 'Maksimal 6 pilihan jawaban.',
            'options.*.text.required' => 'Teks pilihan jawaban wajib diisi.',
            'options.*.text.max' => 'Teks pilihan jawaban maksimal 255 karakter.',
            'correct_option.required' => 'Harus memilih jawaban yang benar.',
        ]);

        DB::transaction(function () use ($validated, $question) {
            $question->update([
                'question' => $validated['question'],
                'type' => 'multiple_choice',
                'points' => $validated['points'],
            ]);

            $question->options()->delete();
            foreach ($validated['options'] as $index => $option) {
                $isCorrect = ($index == $validated['correct_option']);

                $question->options()->create([
                    'option_text' => $option['text'],
                    'is_correct' => $isCorrect,
                    'order' => $index + 1,
                ]);
            }
        });

        return redirect()->route('mentor.quizzes.show', $quiz)
            ->with('success', 'Soal berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quiz $quiz, QuizQuestion $question)
    {
        $this->authorize('update', $quiz);

        DB::transaction(function () use ($question) {
            $question->options()->delete();
            $question->delete();
        });

        return redirect()->route('mentor.quizzes.show', $quiz)
            ->with('success', 'Soal berhasil dihapus!');
    }
}
