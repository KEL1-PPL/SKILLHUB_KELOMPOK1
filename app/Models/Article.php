<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['title', 'content', 'status', 'mentor_id'];

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function isPublished()
    {
        return $this->status === 'published';
    }
}
