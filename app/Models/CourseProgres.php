<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseProgres extends Model
{
    protected $guarded = ['id'];

    public function enrollment() {
        return $this->belongsTo(CourseEnrollment::class);
    }

}
