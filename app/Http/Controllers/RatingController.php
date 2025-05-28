<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Agar user harus login
    }

    public function index()
    {
        $ratings = Rating::with('course')->get();  // Mengambil rating beserta kursusnya
        $title = 'Daftar Rating';
        return view('features.ratings.index', compact('ratings', 'title'));
    }

    public function create()
    {
        $courses = Course::all();  // Mengambil semua data kursus yang ada di tabel courses
        $title = 'Tambah Rating';
        return view('features.ratings.create', compact('courses', 'title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'value' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
        ]);

        Rating::create([
            'value' => $request->value,
            'comment' => $request->comment,
            'course_id' => $request->course_id,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('ratings.index')->with('success', 'Rating berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $rating = Rating::findOrFail($id);
        $courses = Course::all();
        $title = 'Edit Rating';
        return view('features.ratings.edit', compact('rating', 'courses', 'title'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'value' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
        ]);

        $rating = Rating::findOrFail($id);
        $rating->update([
            'value' => $request->value,
            'comment' => $request->comment,
            'course_id' => $request->course_id,
        ]);

        return redirect()->route('ratings.index')->with('success', 'Rating berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $rating = Rating::findOrFail($id);
        $rating->delete();

        return redirect()->route('ratings.index')->with('success', 'Rating berhasil dihapus!');
    }
}
