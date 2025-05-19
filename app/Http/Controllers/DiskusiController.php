<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Diskusi;

class DiskusiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $diskusi = Diskusi::all();
        return response()->json($diskusi);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('diskusi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            // tambahkan field lain sesuai kebutuhan
        ]);

        $diskusi = Diskusi::create($validated);

        return response()->json([
            'message' => 'Diskusi berhasil dibuat!',
            'data' => $diskusi
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $diskusi = Diskusi::findOrFail($id);
        return response()->json($diskusi);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $diskusi = Diskusi::findOrFail($id);
        return view('diskusi.edit', compact('diskusi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            // tambahkan validasi lain sesuai kebutuhan
        ]);

        $diskusi = Diskusi::findOrFail($id);
        $diskusi->update($validated);

        return response()->json([
            'message' => 'Diskusi berhasil diperbarui!',
            'data' => $diskusi
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $diskusi = Diskusi::findOrFail($id);
        $diskusi->delete();

        return response()->json([
            'message' => 'Diskusi berhasil dihapus!'
        ]);
    }
}
