<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Menampilkan semua wishlist.
     */
    public function index()
    {
        $wishlist = Wishlist::all();
        $title = 'Wishlist';
        return view('all.wishlist.index', compact('wishlist', 'title'));
    }

    /**
     * Menyimpan data ke wishlist.
     */
    public function store(Request $request)
    {
        Wishlist::create([
            'title' => $request->title,
            'desc' => $request->desc,
            'image' => $request->image,
            'rating' => $request->rating,
        ]);

        return redirect()->back()->with('success', 'Kursus berhasil ditambahkan ke wishlist!');
    }

    /**
     * Menghapus data dari wishlist.
     */
    public function destroy(string $id)
    {
        $wishlist = Wishlist::findOrFail($id);
        $wishlist->delete();

        return redirect()->back()->with('success', 'Kursus berhasil dihapus dari wishlist.');
    }
}
