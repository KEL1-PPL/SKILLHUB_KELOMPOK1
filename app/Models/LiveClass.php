<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class LiveClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'datetime',
        'platform',
        'link',
        'user_id', // if you want to track who created the live class
        'participants_count',
        'status',
    ];

    protected $casts = [
        'datetime' => 'datetime',
    ];

    // Relationship with User (if you want to track who created the live class)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Check if live class is upcoming
    public function isUpcoming()
    {
        return $this->datetime > now();
    }

    // Check if live class is live (happening now)
    public function isLive()
    {
        $now = now();
        $start = $this->datetime;
        $end = $this->datetime->addHours(2); // Assume 2 hours duration
        
        return $now >= $start && $now <= $end;
    }

    // Check if live class is completed
    public function isCompleted()
    {
        return $this->datetime->addHours(2) < now(); // Assume 2 hours duration
    }

    // Get status
    public function getStatusAttribute()
    {
        if ($this->isLive()) {
            return 'live';
        } elseif ($this->isUpcoming()) {
            return 'upcoming';
        } else {
            return 'completed';
        }
    }

    // Get formatted datetime
    public function getFormattedDatetimeAttribute()
    {
        return $this->datetime->format('d F Y, H:i');
    }

    // Get time until live class starts
    public function getTimeUntilStartAttribute()
    {
        if ($this->isUpcoming()) {
            return $this->datetime->diffForHumans();
        }
        return null;
    }

    // Scope for upcoming live classes
    public function scopeUpcoming($query)
    {
        return $query->where('datetime', '>', now());
    }

    // Scope for live classes happening now
    public function scopeLive($query)
    {
        $now = now();
        return $query->where('datetime', '<=', $now)
                    ->where('datetime', '>=', $now->subHours(2));
    }

    // Scope for completed live classes
    public function scopeCompleted($query)
    {
        return $query->where('datetime', '<', now()->subHours(2));
    }
}