<?php

// ======= app/Http/Controllers/RatingReviewController.php =======
namespace App\Http\Controllers;

use App\Models\RatingReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RatingReviewController extends Controller
{
    public function index()
    {
        $reviews = RatingReview::all();
        return view('ratingreview.index', compact('reviews'));
    }

    public function create()
    {
        return view('ratingreview.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('reviews', 'public');
        }

        RatingReview::create([
            'product_name' => $request->product_name,
            'rating' => $request->rating,
            'review' => $request->review,
            'image' => $imagePath,
        ]);

        return redirect()->route('ratingreview.index');
    }

    public function show($id)
    {
        $review = RatingReview::findOrFail($id);
        return view('ratingreview.show', compact('review'));
    }

    public function edit($id)
    {
        $review = RatingReview::findOrFail($id);
        return view('ratingreview.edit', compact('review'));
    }

    public function update(Request $request, $id)
    {
        $review = RatingReview::findOrFail($id);

        $request->validate([
            'product_name' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $review->image;
        if ($request->hasFile('image')) {
            if ($imagePath) {
                Storage::delete('public/' . $imagePath);
            }
            $imagePath = $request->file('image')->store('reviews', 'public');
        }

        $review->update([
            'product_name' => $request->product_name,
            'rating' => $request->rating,
            'review' => $request->review,
            'image' => $imagePath,
        ]);

        return redirect()->route('ratingreview.index');
    }

    public function destroy($id)
    {
        $review = RatingReview::findOrFail($id);
        if ($review->image) {
            Storage::delete('public/' . $review->image);
        }
        $review->delete();
        return redirect()->route('ratingreview.index');
    }
}

