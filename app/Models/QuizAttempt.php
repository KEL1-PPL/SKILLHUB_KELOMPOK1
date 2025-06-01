<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id',
        'user_id',
        'attempt_number',
        'score',
        'total_points',
        'earned_points',
        'status',
        'started_at',
        'submitted_at',
        'time_taken'
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'total_points' => 'integer',
        'earned_points' => 'integer',
        'time_taken' => 'integer',
        'attempt_number' => 'integer',
        'started_at' => 'datetime',
        'submitted_at' => 'datetime'
    ];

    /**
     * Get the quiz that this attempt belongs to
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Get the user who made this attempt
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all answers for this attempt
     */
    public function answers()
    {
        return $this->hasMany(QuizAnswer::class, 'attempt_id');
    }

    /**
     * Scope for completed attempts
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for in progress attempts
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    /**
     * Check if attempt is passed
     */
    public function isPassed()
    {
        return $this->score >= $this->quiz->passing_score;
    }

    /**
     * Get duration in human readable format
     */
    public function getFormattedDurationAttribute()
    {
        if (!$this->time_taken) {
            return 'N/A';
        }

        $minutes = floor($this->time_taken / 60);
        $seconds = $this->time_taken % 60;

        if ($minutes > 0) {
            return $minutes . 'm ' . $seconds . 's';
        }

        return $seconds . 's';
    }

    /**
     * Get the percentage score
     */
    public function getPercentageAttribute()
    {
        if ($this->total_points == 0) {
            return 0;
        }

        return round(($this->earned_points / $this->total_points) * 100, 2);
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'completed' => $this->isPassed() ? 'success' : 'danger',
            'in_progress' => 'warning',
            'abandoned' => 'secondary',
            default => 'secondary'
        };
    }

    /**
     * Get remaining time in seconds
     */
    public function getRemainingTime()
    {
        if (!$this->quiz->time_limit || $this->status !== 'in_progress') {
            return null;
        }

        $timeLimit = $this->quiz->time_limit * 60;
        $elapsed = now()->diffInSeconds($this->started_at);

        return max(0, $timeLimit - $elapsed);
    }

    /**
     * Check if attempt is expired
     */
    public function isExpired()
    {
        if (!$this->quiz->time_limit || $this->status !== 'in_progress') {
            return false;
        }

        return $this->getRemainingTime() <= 0;
    }

    /**
     * Calculate and update the score
     */
    public function calculateScore()
    {
        $totalPoints = 0;
        $earnedPoints = 0;

        foreach ($this->quiz->questions as $question) {
            $totalPoints += $question->points;

            $answer = $this->answers()->where('question_id', $question->id)->first();
            if ($answer && $answer->points_earned) {
                $earnedPoints += $answer->points_earned;
            }
        }

        $this->update([
            'total_points' => $totalPoints,
            'earned_points' => $earnedPoints,
            'score' => $totalPoints > 0 ? ($earnedPoints / $totalPoints) * 100 : 0
        ]);

        return $this;
    }

    /**
     * Submit the attempt
     */
    public function submit()
    {
        $this->update([
            'status' => 'completed',
            'submitted_at' => now(),
            'time_taken' => $this->started_at ? now()->diffInSeconds($this->started_at) : 0
        ]);

        $this->calculateScore();

        return $this;
    }

    /**
     * Abandon the attempt
     */
    public function abandon()
    {
        $this->update([
            'status' => 'abandoned',
            'submitted_at' => now(),
            'time_taken' => $this->started_at ? now()->diffInSeconds($this->started_at) : 0
        ]);

        return $this;
    }

    /**
     * Scope for attempts by specific user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for attempts of specific quiz
     */
    public function scopeForQuiz($query, $quizId)
    {
        return $query->where('quiz_id', $quizId);
    }

    /**
     * Scope for passed attempts
     */
    public function scopePassed($query)
    {
        return $query->whereHas('quiz', function ($q) {
            $q->whereRaw('quiz_attempts.score >= quizzes.passing_score');
        });
    }

    /**
     * Scope for failed attempts
     */
    public function scopeFailed($query)
    {
        return $query->whereHas('quiz', function ($q) {
            $q->whereRaw('quiz_attempts.score < quizzes.passing_score');
        });
    }

    /**
     * Get attempt status text
     */
    public function getStatusTextAttribute()
    {
        return match ($this->status) {
            'completed' => 'Selesai',
            'in_progress' => 'Sedang Berlangsung',
            'abandoned' => 'Dibatalkan',
            default => 'Tidak Diketahui'
        };
    }

    /**
     * Check if attempt can be continued
     */
    public function canBeContinued()
    {
        return $this->status === 'in_progress' && !$this->isExpired();
    }
}
