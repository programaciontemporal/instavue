<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
    ];

    /**
     * El usuario que dio "Me gusta".
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * La publicación a la que se dio "Me gusta".
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
