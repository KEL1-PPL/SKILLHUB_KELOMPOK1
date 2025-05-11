<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LiveClass extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'mentor_id',
        'title',
        'description',
        'scheduled_at',
        'platform',
        'access_link',
        'max_participants',
        'is_active',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function mentor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(LiveClassRegistration::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'live_class_registrations', 'live_class_id', 'student_id')
            ->withPivot('registered_at', 'attended')
            ->withTimestamps();
    }

    public function hasAvailableSeats(): bool
    {
        if (!$this->max_participants) {
            return true;
        }

        return $this->registrations()->count() < $this->max_participants;
    }

    public function isUpcoming(): bool
    {
        return $this->scheduled_at->isFuture();
    }

    public function isUserRegistered(User $user): bool
    {
        return $this->registrations()->where('student_id', $user->id)->exists();
    }

}
