<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function index()
    {
        $ratings = Rating::all();
        return view('features.ratings.index', compact('ratings'));
    }

    public function create()
    {
        return view('features.ratings.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'value' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string',
        'course_id' => 'nullable|exists:courses,id',
    ]);

    Rating::create([
        'value' => $request->value,
        'comment' => $request->comment,
        'course_id' => $request->course_id, // ini boleh null kalau belum ada
        'user_id' => auth()->id(), // ambil user dari session login
    ]);

    return redirect()->route('features.ratings.index')->with('success', 'Rating berhasil ditambahkan!');
}
    

    public function edit($id)
    {
        $rating = Rating::findOrFail($id);
        return view('features.ratings.edit', compact('rating'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'value' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $rating = Rating::findOrFail($id);
        $rating->update([
            'value' => $request->value,
            'comment' => $request->comment,
        ]);

        return redirect()->route('features.ratings.index');
    }

    public function destroy($id)
    {
        $rating = Rating::findOrFail($id);
        $rating->delete();

        return redirect()->route('features.ratings.index');
    }
}
