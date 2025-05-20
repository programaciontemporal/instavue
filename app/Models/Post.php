<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'caption',
        'image_path',
    ];

    /**
     * El usuario que publicó este post.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Comentarios de este post.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Likes de este post.
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}
