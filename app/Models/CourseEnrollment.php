<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseEnrollment extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function progress()
    {
        return $this->hasOne(CourseProgres::class, 'enrollment_id');
    }

    public function calculateProgress()
    {
        $materials = $this->course->materials;
        $userId = $this->user_id;

        $total = $materials->count();

        if ($total === 0) return 0;

        $completed = $materials->filter(function ($material) use ($userId) {
            return $material->completions->where('user_id', $userId)->where('is_completed', true)->isNotEmpty();
        })->count();

        return round(($completed / $total) * 100, 2);
    }
}
