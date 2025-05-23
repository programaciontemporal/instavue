<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // Importa la clase Str para usar Str::title()

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'bio',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // INICIO DEL CAMBIO

    // Agregamos 'avatar_url' a los atributos que se añadirán automáticamente
    // cuando el modelo se convierta a un array o JSON (para Inertia).
    protected $appends = ['avatar_url'];

    /**
     * Accesor para la URL completa del avatar o las iniciales del usuario.
     * Este accesor siempre devolverá una URL.
     */
    public function getAvatarUrlAttribute()
    {
        // Si el usuario tiene un avatar guardado en la base de datos
        if ($this->attributes['avatar']) {
            // Usa el valor original del atributo 'avatar' para obtener la URL de Storage.
            // Es importante usar $this->attributes['avatar'] aquí para evitar recursión
            // con el accesor getAvatarAttribute si existiera.
            return Storage::url($this->attributes['avatar']);
        }

        // Si no hay avatar, generamos una URL usando el servicio ui-avatars.com
        // Esto crea una imagen con las iniciales del nombre del usuario.
        // `Str::title()` capitaliza la primera letra de cada palabra del nombre.
        $name = urlencode(Str::title($this->name));
        // Puedes personalizar el color de fondo (background) y el color del texto (color)
        // en esta URL. Aquí, fondo azul y texto blanco.
        return "https://ui-avatars.com/api/?name={$name}&color=ffffff&background=007bff&size=128";
    }

    // FIN DEL CAMBIO

    // Tu accesor getAvatarAttribute actual es redundante si vas a usar getAvatarUrlAttribute.
    // Te recomiendo eliminarlo para simplificar el código.
    // Si lo mantienes, asegúrate de no usarlo para la lógica de visualización en Vue,
    // ya que getAvatarUrlAttribute ya maneja la URL de las iniciales.
    /*
    public function getAvatarAttribute($value)
    {
        return ($value === '' || $value === null) ? null : Storage::url($value);
    }
    */

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
     * @param   \App\Models\User    $user
     * @return bool
     */
    public function isFollowing(User $user): bool
    {
        return $this->following()->where('following_user_id', $user->id)->exists();
    }

    /**
     * Check if the current user has saved a post.
     *
     * @param   \App\Models\Post    $post
     * @return bool
     */
    public function hasSaved(Post $post): bool
    {
        return $this->savedPosts()->where('post_id', $post->id)->exists();
    }
}