<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class LikeController extends Controller
{
    /**
     * Registra un "Me gusta" del usuario autenticado en una publicación.
     * Utiliza firstOrCreate para evitar duplicados.
     */
    public function store(Post $post): RedirectResponse
    {
        Like::firstOrCreate([
            'user_id' => Auth::id(),
            'post_id' => $post->id,
        ]);

        return back();
    }

    /**
     * Elimina el "Me gusta" del usuario autenticado de una publicación.
     */
    public function destroy(Post $post): RedirectResponse
    {
        // Eliminar el "Me gusta"
        Like::where('user_id', Auth::id())
            ->where('post_id', $post->id)
            ->delete();

        return back();
    }
}
