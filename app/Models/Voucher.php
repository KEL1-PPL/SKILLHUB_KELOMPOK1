<?php

// ======= app/Models/Voucher.php =======
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'discount_percentage', 'image_url', 'redeemed'
    ];

    protected $casts = [
        'redeemed' => 'boolean',
    ];
}
