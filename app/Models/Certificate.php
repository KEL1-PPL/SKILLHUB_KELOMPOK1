<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'certificate_number',
        'certificate_file',
        'issued_at',
    ];

    // Relasi ke user (siswa)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke course (kursus)
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
