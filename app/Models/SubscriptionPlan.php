<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'duration_in_days',
        'features',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration_in_days' => 'integer',
        'features' => 'array',
        'is_active' => 'boolean'
    ];

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getFormattedDurationAttribute(): string
    {
        if ($this->duration_in_days % 30 === 0) {
            $months = $this->duration_in_days / 30;
            return $months . ' ' . str('month')->plural($months);
        }
        
        return $this->duration_in_days . ' ' . str('day')->plural($this->duration_in_days);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function calculateExpiryDate(): string
    {
        return now()->addDays($this->duration_in_days)->format('Y-m-d H:i:s');
    }

}
