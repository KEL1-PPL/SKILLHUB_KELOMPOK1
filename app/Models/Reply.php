<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $fillable = ['diskusi_id', 'user_id', 'content', 'is_best_answer'];

    public function diskusi() {
        return $this->belongsTo(Diskusi::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}

