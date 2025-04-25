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
        return view('features.ratings.index', compact('ratings'));
    }

    public function create()
    {
        // Ambil semua kursus dari database
        $courses = Course::all();  // Mengambil semua data kursus yang ada di tabel courses

        return view('features.ratings.create', compact('courses'));  // Kirim data kursus ke view
    }

    public function store(Request $request)
    {
        $request->validate([
            'value' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',  // Validasi untuk memastikan course_id yang diterima adalah ID yang valid
        ]);

        // Simpan data rating ke dalam database
        Rating::create([
            'value' => $request->value,
            'comment' => $request->comment,
            'course_id' => $request->course_id,  // Menyimpan ID kursus yang dipilih
            'user_id' => Auth::id(),  // Menyimpan ID user yang memberikan rating
        ]);

        return redirect()->route('ratings.index')->with('success', 'Rating berhasil ditambahkan!');
    }

    public function edit($id)
    {
        // Ambil data rating dan semua kursus
        $rating = Rating::findOrFail($id);
        $courses = Course::all(); // Ambil semua kursus
        return view('features.ratings.edit', compact('rating', 'courses'));  // Kirim data rating dan kursus ke view
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'value' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',  // Validasi untuk memastikan course_id yang diterima adalah ID yang valid
        ]);

        $rating = Rating::findOrFail($id);
        $rating->update([
            'value' => $request->value,
            'comment' => $request->comment,
            'course_id' => $request->course_id,  // Memperbarui course_id
        ]);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('ratings.index')->with('success', 'Rating berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $rating = Rating::findOrFail($id);
        $rating->delete();

        return redirect()->route('ratings.index')->with('success', 'Rating berhasil dihapus!');
    }
}
