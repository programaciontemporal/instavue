<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Modelo User - Representa un usuario en la aplicación
 *
 * Este modelo gestiona toda la información relacionada con los usuarios,
 * incluyendo sus datos personales, autenticación y relaciones con otros modelos.
 *
 * @property string $name Nombre del usuario
 * @property string $email Email único del usuario
 * @property string $password Contraseña hasheada del usuario
 * @property string|null $avatar Ruta del archivo de avatar del usuario
 * @property string|null $bio Biografía o descripción del usuario
 * @property \DateTime|null $email_verified_at Fecha de verificación del email
 * @property string $remember_token Token para recordar sesión
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'bio',
    ];

    /**
     * Los atributos que deben ocultarse en las arrays/JSON.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Los atributos que deben ser añadidos a las arrays/JSON.
     *
     * @var array<int, string>
     */
    protected $appends = ['avatar_url'];

    /**
     * Obtiene la URL completa del avatar del usuario
     *
     * Si el usuario tiene un avatar guardado, devuelve la URL del archivo.
     * En caso contrario, devuelve una URL para generar un avatar con las iniciales.
     *
     * @return string URL del avatar o URL para generar avatar con iniciales
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

    /**
     * Obtiene los usuarios que este usuario está siguiendo
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function following()
    {
        return $this->belongsToMany(User::class, 'follows', 'user_id', 'following_user_id');
    }

    /**
     * Obtiene los seguidores de este usuario
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_user_id', 'user_id');
    }

    /**
     * Obtiene todas las publicaciones creadas por este usuario
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Obtiene todos los "me gusta" dados por este usuario
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Obtiene todos los comentarios realizados por este usuario
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Obtiene las publicaciones guardadas por este usuario
     *
     * Esta relación muchos a muchos permite a los usuarios guardar publicaciones
     * para verlas más tarde, similar a los marcadores
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function savedPosts()
    {
        return $this->belongsToMany(Post::class, 'saved_posts', 'user_id', 'post_id')->withTimestamps();
    }

    /**
     * Verifica si el usuario actual está siguiendo a otro usuario
     *
     * @param \App\Models\User $user El usuario a verificar
     * @return bool true si el usuario actual sigue al usuario especificado
     */
    public function isFollowing(User $user): bool
    {
        return $this->following()->where('following_user_id', $user->id)->exists();
    }

    /**
     * Verifica si el usuario ha guardado una publicación específica
     *
     * @param \App\Models\Post $post La publicación a verificar
     * @return bool true si el usuario ha guardado la publicación
     */
    public function hasSaved(Post $post): bool
    {
        return $this->savedPosts()->where('post_id', $post->id)->exists();
    }
}
