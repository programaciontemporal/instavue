<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Usuarios que han guardado esta publicación.
     */
    public function savers()
    {
        return $this->belongsToMany(User::class, 'saved_posts', 'post_id', 'user_id')->withTimestamps();
    }

    /**
     * Accesor para la URL completa de la imagen.
     */
    public function getImagePathAttribute($value)
    {
        return $value ? Storage::url($value) : null;
    }
}