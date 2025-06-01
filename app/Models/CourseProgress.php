<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseProgress extends Model
{
    protected $table = 'course_progress';
    protected $guarded = ['id'];

    protected $casts = [
        'last_accessed_at' => 'datetime',
        'percentage_completed' => 'float'
    ];

    public function enrollment()
    {
        return $this->belongsTo(CourseEnrollment::class, 'enrollment_id');
    }
} 