<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = ['value', 'comment', 'user_id', 'course_id'];

    // Menambahkan relasi ke model Course
   public function user()
{
    return $this->belongsTo(User::class);
}

public function course()
{
    return $this->belongsTo(Course::class);
}

}
