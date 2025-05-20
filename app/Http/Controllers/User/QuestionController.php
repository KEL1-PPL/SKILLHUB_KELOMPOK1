<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    // Tampilkan daftar soal untuk satu kuis
    public function index(Quiz $quiz)
    {
        $quiz->load('questions.options');
        return view('quiz.question.list', compact('quiz'));
    }

    // Form tambah soal
    public function create(Quiz $quiz)
    {
        return view('quiz.question.create', compact('quiz'));
    }

    // Simpan soal baru
    public function store(Request $request, Quiz $quiz)
    {
        $request->validate([
            'question_text' => 'required|string',
            'options' => 'required|array|min:2',
            'correct_option' => 'required|integer',
        ]);

        // Buat pertanyaannya
        $question = $quiz->questions()->create([
            'question_text' => $request->question_text,
        ]);

        // Tambahkan pilihan jawaban
        foreach ($request->options as $index => $option) {
            $question->options()->create([
                'option_text' => $option['option_text'],
                'is_correct' => $index == $request->correct_option,
            ]);
        }

        return redirect()->route('questions.index', $quiz)->with('success', 'Soal berhasil ditambahkan.');
    }

    // Form edit soal
    public function edit(Question $question)
    {
        $question->load('options');
        return view('quiz.question.edit', compact('question'));
    }

    // Update soal
    public function update(Request $request, Question $question)
    {
        $request->validate([
            'question_text' => 'required|string',
            'options' => 'required|array|min:2',
            'correct_option' => 'required|integer',
        ]);

        // Update soal
        $question->update([
            'question_text' => $request->question_text,
        ]);

        // Hapus pilihan lama
        $question->options()->delete();

        // Tambahkan pilihan baru
        foreach ($request->options as $index => $option) {
            $question->options()->create([
                'option_text' => $option['option_text'],
                'is_correct' => $index == $request->correct_option,
            ]);
        }

        return redirect()->route('questions.index', $question->quiz_id)->with('success', 'Soal berhasil diperbarui.');
    }

    // Hapus soal
    public function destroy(Question $question)
    {
        $quizId = $question->quiz_id;
        $question->options()->delete();
        $question->delete();

        return redirect()->route('questions.index', $quizId)->with('success', 'Soal berhasil dihapus.');
    }
}
