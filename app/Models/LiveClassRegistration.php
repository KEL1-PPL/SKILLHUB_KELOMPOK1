<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiveClassRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'live_class_id',
        'student_id',
        'registered_at',
        'attended',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
        'attended' => 'boolean',
    ];

    public function liveClass(): BelongsTo
    {
        return $this->belongsTo(LiveClass::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}