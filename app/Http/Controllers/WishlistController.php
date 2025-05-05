<?php
namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WishlistController extends Controller
{
    // Fungsi untuk menambahkan kursus ke wishlist
    public function addToWishlist(Request $request)
    {
        $course = Course::findOrFail($request->course_id);
        $user = auth()->user();
    
        // Cek jika kursus sudah ada di wishlist
        $existingWishlist = Wishlist::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();
    
        if ($existingWishlist) {
            return response()->json([
                'success' => false, 
                'message' => 'Kursus sudah ada di wishlist.'
            ]);
        }
    
        // Menambahkan kursus ke wishlist
        Wishlist::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
        ]);
        
        return response()->json([
            'success' => true, 
            'message' => 'Kursus berhasil ditambahkan ke wishlist.'
        ]);
    }

    // Fungsi untuk menghapus kursus dari wishlist
    public function removeFromWishlist(Request $request)
    {
        // Cari kursus berdasarkan ID
        $course = Course::findOrFail($request->course_id);

        // Ambil user yang sedang login
        $user = auth()->user();

        // Hapus kursus dari wishlist
        $deleted = Wishlist::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->delete();
                
        return response()->json([
            'success' => true,
            'message' => 'Kursus berhasil dihapus dari wishlist.'
        ]);
    }

    // Fungsi untuk menampilkan wishlist
    public function showWishlist()
    {
        // Ambil user yang sedang login
        $user = auth()->user();

        // Ambil wishlist berdasarkan user_id
        $wishlists = Wishlist::with('course')
                            ->where('user_id', $user->id)
                            ->get();

        // Kirim data wishlist ke view
        return view('all.wishlist.index', [
            'title' => 'Wishlist',
            'wishlists' => $wishlists,
        ]);
    }

    public function showWishlistAll()
    {
        try {
            // Mengambil semua wishlist dengan kursus dan pengguna yang terhubung
            $wishlists = Wishlist::with(['course', 'user'])->get();
        } catch (\Exception $e) {
            // Menangkap kesalahan dan menampilkannya di log
            \Log::error('Error fetching wishlist: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Error fetching wishlist.');
        }
    
        // Menampilkan data ke tampilan
        return view('all.wishlist.index', ['wishlists' => $wishlists]);
    }
    
    
    // Fungsi untuk menampilkan halaman wishlist
    public function index()
    {
        // Ambil user yang sedang login
        $user = auth()->user();

        // Ambil wishlist berdasarkan user_id
        $wishlists = Wishlist::with('course')
                            ->where('user_id', $user->id)
                            ->get();

        // Kirim data wishlist ke view
        return view('all.wishlist.index', [
            'title' => 'Wishlist',
            'wishlists' => $wishlists,
        ]);
    }
}
