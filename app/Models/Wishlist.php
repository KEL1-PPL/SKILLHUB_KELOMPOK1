<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    // Nama tabel yang digunakan (karena bukan bentuk jamak)
    protected $table = 'wishlists'; // Sesuaikan dengan nama tabel yang sudah dibuat

    // Kolom-kolom yang bisa diisi lewat mass assignment
    protected $fillable = [
        'user_id', // Mengacu pada pengguna yang membuat wishlist
        'course_id', // Mengacu pada kursus yang dimasukkan ke wishlist
    ];

    // Relasi dengan User (Pengguna yang menambahkan kursus ke wishlist)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi dengan Course (Kursus yang ada di wishlist)
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
