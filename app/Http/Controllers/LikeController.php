<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post; // Importar el modelo Post
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class LikeController extends Controller
{
    /**
     * Almacena un nuevo "Me gusta" para una publicación.
     */
    public function store(Post $post): RedirectResponse
    {
        // Asegurarse de que el usuario no pueda dar "Me gusta" a su propia publicación si es necesario
        // if (Auth::id() === $post->user_id) {
        //     return back()->withErrors(['error' => 'No puedes dar "Me gusta" a tu propia publicación.']);
        // }

        // Crear el "Me gusta" si no existe
        Like::firstOrCreate([
            'user_id' => Auth::id(),
            'post_id' => $post->id,
        ]);

        return back();
    }

    /**
     * Elimina un "Me gusta" de una publicación.
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
