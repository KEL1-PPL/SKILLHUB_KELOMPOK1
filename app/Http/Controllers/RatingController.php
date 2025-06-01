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

    /**
     * Menampilkan daftar rating
     */
    public function index()
    {
        $ratings = Rating::with('course')->get();  // Mengambil rating beserta kursusnya
        $title = 'Daftar Rating';

        return view('features.ratings.index', compact('ratings', 'title'));
    }

    /**
     * Menampilkan form untuk menambah rating
     */
    public function create()
    {
        $courses = Course::all();  // Mengambil semua data kursus yang ada di tabel courses
        $title = 'Tambah Rating';

        return view('features.ratings.create', compact('courses', 'title'));
    }

    /**
     * Menyimpan rating baru
     */
    public function store(Request $request)
    {
        // Validasi input rating
        $validated = $request->validate([
            'value' => 'required|integer|min:1|max:5',  // Rating hanya 1-5
            'comment' => 'nullable|string',  // Komentar bersifat opsional
            'course_id' => 'required|exists:courses,id',  // Validasi kursus yang dipilih ada
        ]);

        // Membuat dan menyimpan rating baru
        Rating::create([
            'value' => $validated['value'],
            'comment' => $validated['comment'],
            'course_id' => $validated['course_id'],
            'user_id' => Auth::id(),  // Ambil ID user yang sedang login
        ]);

        return redirect()->route('ratings.index')->with('success', 'Rating berhasil ditambahkan!');
    }

    /**
     * Menampilkan form untuk mengedit rating
     */
    public function edit($id)
    {
        // Cari rating berdasarkan ID
        $rating = Rating::findOrFail($id);
        $courses = Course::all();
        $title = 'Edit Rating';

        return view('features.ratings.edit', compact('rating', 'courses', 'title'));
    }

    /**
     * Memperbarui rating
     */
    public function update(Request $request, $id)
    {
        // Validasi input rating
        $validated = $request->validate([
            'value' => 'required|integer|min:1|max:5',  // Rating hanya 1-5
            'comment' => 'nullable|string',  // Komentar bersifat opsional
            'course_id' => 'required|exists:courses,id',  // Validasi kursus yang dipilih ada
        ]);

        // Cari rating berdasarkan ID dan update
        $rating = Rating::findOrFail($id);
        $rating->update([
            'value' => $validated['value'],
            'comment' => $validated['comment'],
            'course_id' => $validated['course_id'],
        ]);

        return redirect()->route('ratings.index')->with('success', 'Rating berhasil diperbarui!');
    }

    /**
     * Menghapus rating
     */
    public function destroy($id)
    {
        // Cari dan hapus rating berdasarkan ID
        $rating = Rating::findOrFail($id);
        $rating->delete();

        return redirect()->route('ratings.index')->with('success', 'Rating berhasil dihapus!');
    }
}
