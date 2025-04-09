<?php

// ======= app/Models/RatingReview.php =======
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RatingReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name', 'rating', 'review', 'image'
    ];
}
