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
        'user_id', 
        'participants_count',
        'status',
    ];

    protected $casts = [
        'datetime' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isUpcoming()
    {
        return $this->datetime > now();
    }

    public function isLive()
    {
        $now = now();
        $start = $this->datetime;
        $end = $this->datetime->copy()->addHours(2); 

        return $now >= $start && $now <= $end;
    }

    public function isCompleted()
    {
        return $this->datetime->copy()->addHours(2)->lt(now());
    }

    public function getLiveStatusAttribute()
    {
        if ($this->isLive()) {
            return 'live';
        } elseif ($this->isUpcoming()) {
            return 'upcoming';
        } else {
            return 'completed';
        }
    }

    public function getFormattedDatetimeAttribute()
    {
        return $this->datetime->format('d F Y, H:i');
    }

    public function getTimeUntilStartAttribute()
    {
        if ($this->isUpcoming()) {
            return $this->datetime->diffForHumans();
        }
        return null;
    }

    public function scopeUpcoming($query)
    {
        return $query->where('datetime', '>', now());
    }

    public function scopeLive($query)
    {
        $now = now();
        return $query->where('datetime', '<=', $now)
                    ->where('datetime', '>', $now->copy()->subHours(2));
    }

    public function scopeCompleted($query)
    {
        return $query->where('datetime', '<=', now()->subHours(2));
    }

    public function getDebugStatusAttribute()
    {
        $now = now();
        return [
            'current_time' => $now->format('Y-m-d H:i:s'),
            'class_datetime' => $this->datetime->format('Y-m-d H:i:s'),
            'class_end' => $this->datetime->copy()->addHours(2)->format('Y-m-d H:i:s'),
            'is_upcoming' => $this->isUpcoming(),
            'is_live' => $this->isLive(),
            'is_completed' => $this->isCompleted(),
            'database_status' => $this->attributes['status'] ?? 'unknown'
        ];
    }
}