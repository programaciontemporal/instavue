<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'bio', // Asegúrate de que 'bio' también esté en fillable si es editable
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Accesor para la URL completa del avatar.
     * Convierte la cadena vacía a null si no hay avatar.
     */
    public function getAvatarAttribute($value)
    {
        return ($value === '' || $value === null) ? null : Storage::url($value);
    }

    // Relaciones de Seguimiento
    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'user_id', 'following_user_id');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_user_id', 'user_id');
    }

    // Relaciones de Contenido
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Publicaciones guardadas por este usuario.
     */
    public function savedPosts()
    {
        return $this->belongsToMany(Post::class, 'saved_posts', 'user_id', 'post_id')->withTimestamps();
    }

    /**
     * Check if the current user is following another user.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function isFollowing(User $user): bool
    {
        return $this->following()->where('following_user_id', $user->id)->exists();
    }

    /**
     * Check if the current user has saved a post.
     *
     * @param  \App\Models\Post  $post
     * @return bool
     */
    public function hasSaved(Post $post): bool
    {
        return $this->savedPosts()->where('post_id', $post->id)->exists();
    }
}