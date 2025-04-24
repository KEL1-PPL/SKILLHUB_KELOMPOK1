<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    // Nama tabel yang digunakan (karena bukan bentuk jamak)
    protected $table = 'wishlist';

    // Kolom-kolom yang bisa diisi lewat mass assignment
    protected $fillable = [
        'title',
        'desc',
        'image',
        'rating',
    ];
}
