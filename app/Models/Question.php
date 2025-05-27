<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    // Tambahkan 'explanation' agar bisa diisi melalui form atau seeder
    protected $fillable = ['quiz_id', 'question_text', 'explanation'];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function options()
    {
        return $this->hasMany(Option::class);
    }
}
