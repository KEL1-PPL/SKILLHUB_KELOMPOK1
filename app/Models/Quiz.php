<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'material_id',
        'title',
        'description',
        'time_limit',
        'max_attempts',
        'passing_score',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'time_limit' => 'integer',
        'max_attempts' => 'integer',
        'passing_score' => 'integer'
    ];

    /**
     * Get the course that owns the quiz
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the material that this quiz belongs to (optional)
     */
    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    /**
     * Get the user who created this quiz
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all questions for this quiz
     */
    public function questions()
    {
        return $this->hasMany(QuizQuestion::class)->orderBy('order');
    }

    /**
     * Get all quiz attempts for this quiz
     */
    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class);
    }

    /**
     * Get completed quiz attempts
     */
    public function completedAttempts()
    {
        return $this->hasMany(QuizAttempt::class)->where('status', 'completed');
    }

    /**
     * Scope to get active quizzes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get quizzes by mentor
     */
    public function scopeByMentor($query, $mentorId)
    {
        return $query->where('created_by', $mentorId);
    }

    /**
     * Get total points for this quiz
     */
    public function getTotalPointsAttribute()
    {
        return $this->questions()->sum('points');
    }

    /**
     * Get quiz duration in human readable format
     */
    public function getFormattedDurationAttribute()
    {
        if (!$this->time_limit) {
            return 'Tidak terbatas';
        }

        $hours = floor($this->time_limit / 60);
        $minutes = $this->time_limit % 60;

        if ($hours > 0) {
            return $hours . ' jam ' . ($minutes > 0 ? $minutes . ' menit' : '');
        }

        return $minutes . ' menit';
    }

    /**
     * Check if user can take this quiz
     */
    public function canBeTakenBy($userId)
    {
        if (!$this->is_active) {
            return false;
        }

        $userAttempts = $this->quizAttempts()
            ->where('user_id', $userId)
            ->count();

        return $userAttempts < $this->max_attempts;
    }

    /**
     * Get user's best score for this quiz
     */
    public function getBestScoreFor($userId)
    {
        return $this->quizAttempts()
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->max('score') ?? 0;
    }

    /**
     * Get user's attempt count for this quiz
     */
    public function getAttemptCountFor($userId)
    {
        return $this->quizAttempts()
            ->where('user_id', $userId)
            ->count();
    }

    /**
     * Check if quiz has been passed by user
     */
    public function hasBeenPassedBy($userId)
    {
        $bestScore = $this->getBestScoreFor($userId);
        return $bestScore >= $this->passing_score;
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function userAttempts($userId): HasMany
    {
        return $this->hasMany(QuizAttempt::class)->where('user_id', $userId);
    }
}
