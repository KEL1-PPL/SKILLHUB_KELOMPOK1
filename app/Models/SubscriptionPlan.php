<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'duration_in_days',
        'features'
    ];

    protected $casts = [
        'price' => 'float',
        'duration_in_days' => 'integer',
        'features' => 'array'
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getFormattedDurationAttribute()
    {
        if ($this->duration_in_days >= 365) {
            $years = floor($this->duration_in_days / 365);
            return $years . ' ' . str_plural('tahun', $years);
        } elseif ($this->duration_in_days >= 30) {
            $months = floor($this->duration_in_days / 30);
            return $months . ' ' . str_plural('bulan', $months);
        } else {
            return $this->duration_in_days . ' ' . str_plural('hari', $this->duration_in_days);
        }
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
