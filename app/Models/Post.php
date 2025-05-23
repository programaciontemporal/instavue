<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Modelo Post - Representa una publicación en la aplicación
 *
 * Este modelo gestiona las publicaciones de los usuarios, incluyendo
 * la imagen, el texto descriptivo y todas sus relaciones con otros modelos.
 *
 * @property int $id ID único de la publicación
 * @property int $user_id ID del usuario que creó la publicación
 * @property string $caption Texto descriptivo de la publicación
 * @property string $image_path Ruta del archivo de imagen de la publicación
 * @property \DateTime $created_at Fecha de creación
 * @property \DateTime $updated_at Fecha de última actualización
 */
class Post extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'caption',
        'image_path',
    ];

    /**
     * Obtiene el usuario que creó esta publicación
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene los comentarios asociados a esta publicación
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Obtiene los "me gusta" asociados a esta publicación
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Obtiene los usuarios que han guardado esta publicación
     *
     * Esta relación permite saber qué usuarios han marcado esta publicación
     * como favorita o la han guardado para verla más tarde
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function savers()
    {
        return $this->belongsToMany(User::class, 'saved_posts', 'post_id', 'user_id')->withTimestamps();
    }

    /**
     * Accesor para obtener la URL completa de la imagen
     *
     * Transforma la ruta de almacenamiento en una URL accesible
     *
     * @param string|null $value La ruta almacenada en la base de datos
     * @return string|null La URL completa de la imagen o null si no existe
     */
    public function getImagePathAttribute($value)
    {
        return $value ? Storage::url($value) : null;
    }
}
