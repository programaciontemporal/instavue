<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Comment - Representa un comentario en una publicación
 *
 * Este modelo gestiona los comentarios que los usuarios pueden hacer
 * en las publicaciones de otros usuarios o en las suyas propias.
 *
 * @property int $id ID único del comentario
 * @property int $user_id ID del usuario que hizo el comentario
 * @property int $post_id ID de la publicación comentada
 * @property string $body Contenido del comentario
 * @property \DateTime $created_at Fecha de creación del comentario
 * @property \DateTime $updated_at Fecha de última actualización
 */
class Comment extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'post_id',
        'body',
    ];

    /**
     * Obtiene el usuario que creó este comentario
     *
     * Esta relación permite acceder a la información del usuario
     * que escribió el comentario
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene la publicación a la que pertenece este comentario
     *
     * Esta relación permite acceder a la publicación en la que
     * se hizo el comentario
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
