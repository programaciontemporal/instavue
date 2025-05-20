<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Los posts que el usuario ha publicado.
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Usuarios a los que este usuario está siguiendo.
     */
    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'user_id', 'following_user_id');
    }

    /**
     * Usuarios que siguen a este usuario.
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_user_id', 'user_id');
    }

    /**
     * Comentarios que el usuario ha realizado.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Comprueba si el usuario actual sigue a otro usuario.
     */
    public function isFollowing(User $user)
    {
        return $this->following()->where('following_user_id', $user->id)->exists();
    }

    /**
     * Likes que el usuario ha dado.
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}
