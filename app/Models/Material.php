<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'content',
        'order'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function completions()
    {
        return $this->hasMany(MaterialCompletion::class);
    }

    public function isCompletedByUser($userId)
    {
        return $this->completions()
            ->where('user_id', $userId)
            ->where('is_completed', true)
            ->exists();
    }

    public function markAsCompleted($userId)
    {
        return $this->completions()->updateOrCreate(
            ['user_id' => $userId],
            ['is_completed' => true]
        );
    }

    public function markAsIncomplete($userId)
    {
        return $this->completions()->updateOrCreate(
            ['user_id' => $userId],
            ['is_completed' => false]
        );
    }
}
