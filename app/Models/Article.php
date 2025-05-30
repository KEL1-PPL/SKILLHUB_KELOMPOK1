<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
        'status',
        'user_id',
        'rejected_note',
        'views'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($article) {
            $article->slug = Str::slug($article->title);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
