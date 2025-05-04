<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Earning extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'payment_date',
        'is_valid',
        'correction_note',
    ];

    // Relasi dengan model Mentor
    public function mentor()
    {
        return $this->belongsTo(Mentor::class);
    }

    // Add the scopeValid method here
    public function scopeValid($query)
    {
        return $query->where('is_valid', true);
    }
}