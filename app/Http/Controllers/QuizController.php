<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QuizController extends Controller
{
    /**
     * Tampilkan daftar semua kuis.
     */
    public function index()
    {
        $quizzes = Quiz::latest()->paginate(10); // Gunakan pagination
        return view('quiz.index', compact('quizzes'));
    }

    /**
     * Tampilkan form untuk membuat kuis baru.
     */
    public function create()
    {
        return view('quiz.create');
    }

    /**
     * Simpan kuis baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            Quiz::create([
                'title' => $request->title,
                'description' => $request->description,
            ]);

            return redirect()->route('quizzes.index')
                ->with('success', 'Kuis berhasil dibuat.');
        } catch (\Exception $e) {
            Log::error('Gagal membuat kuis: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan kuis.');
        }
    }
}
