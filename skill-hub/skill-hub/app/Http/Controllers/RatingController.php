<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Menampilkan semua rating
        $ratings = Rating::all();
        return view('ratings.index', compact('ratings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Menampilkan form untuk menambahkan rating
        return view('ratings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'materi_id' => 'required|exists:materials,id', // Ganti 'materials' sesuai nama tabel materi
            'rating' => 'required|numeric|min:1|max:5',
        ]);

        // Menyimpan rating
        Rating::create($validated);

        return redirect()->route('ratings.index')->with('success', 'Rating berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Rating $rating)
    {
        // Menampilkan detail rating
        return view('ratings.show', compact('rating'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rating $rating)
    {
        // Menampilkan form untuk mengedit rating
        return view('ratings.edit', compact('rating'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Rating $rating)
    {
        // Validasi input
        $validated = $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
        ]);

        // Mengupdate rating
        $rating->update($validated);

        return redirect()->route('ratings.index')->with('success', 'Rating berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rating $rating)
    {
        // Menghapus rating
        $rating->delete();

        return redirect()->route('ratings.index')->with('success', 'Rating berhasil dihapus');
    }
}
