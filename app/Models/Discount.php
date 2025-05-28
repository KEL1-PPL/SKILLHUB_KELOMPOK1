<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'percentage',
        'start_date',
        'end_date',
        'description'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'percentage' => 'float',
    ];

    /**
     * Get the course that owns the discount.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Check if the discount is active.
     */
    public function isActive()
    {
        $now = now();
        return $this->start_date <= $now && $this->end_date >= $now;
    }

    /**
     * Scope a query to only include active discounts.
     */
    public function scopeActive($query)
    {
        $now = now();
        return $query->where('start_date', '<=', $now)
                     ->where('end_date', '>=', $now);
    }
}
