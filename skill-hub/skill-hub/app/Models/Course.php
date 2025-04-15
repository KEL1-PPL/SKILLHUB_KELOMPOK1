<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $guarded = ['id'];

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function enrollments() {
        return $this->hasMany(CourseEnrollment::class);
    }

    public function completionHistory() {
        return $this->hasMany(CourseCompletionHistory::class);
    }

    public function analytics() {
        return $this->hasMany(Analytic::class);
    }

    public function mentor()
    {
    return $this->belongsTo(User::class, 'created_by');
    }

    public function materials()
    {
    return $this->hasMany(CourseMaterial::class);
    }


}
