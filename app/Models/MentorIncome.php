<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MentorIncome extends Model
{
    protected $fillable = [
        'mentorId',
        'studentId',
        'amount',
        'transactionDate',
        'status',
        'courseId',
        'subscriptionId',
        'note'
    ];

    protected $casts = [
        'transactionDate' => 'datetime',
        'amount' => 'decimal:2'
    ];

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentorId');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'studentId');
    }
}