<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseProgres extends Model
{
    protected $table = 'course_progress';
    protected $guarded = ['id'];
    protected $table = 'course_progress';

    public function enrollment() {
        return $this->belongsTo(CourseEnrollment::class);
    }

}
