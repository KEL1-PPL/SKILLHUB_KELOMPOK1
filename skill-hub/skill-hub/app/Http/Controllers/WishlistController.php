<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function add($courseId)
    {
        $user = Auth::user();

        $exists = Wishlist::where('user_id', $user->id)
                          ->where('course_id', $courseId)
                          ->exists();

        if (!$exists) {
            Wishlist::create([
                'user_id' => $user->id,
                'course_id' => $courseId
            ]);
        }

        return redirect()->back()->with('success', 'Kursus ditambahkan ke wishlist!');
    }
}
