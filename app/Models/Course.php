<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    public function materials()
    {
        return $this->hasMany(Material::class)->orderBy('order');
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : asset('images/default-course.png');
    }

    public static function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $count = self::where('slug', 'LIKE', "{$slug}%")->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }

    
}
