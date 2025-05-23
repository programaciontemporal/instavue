<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class CommentController extends Controller
{
    /**
     * Crea un nuevo comentario en una publicación.
     * Valida el contenido del comentario y maneja posibles errores de validación.
     */
    public function store(Request $request, Post $post): RedirectResponse
    {
        try {
            $validated = $request->validate([
                'body' => 'required|string|max:255',
            ]);

            $post->comments()->create([
                'user_id' => Auth::id(),
                'body' => $validated['body'],
            ]);

            return back()->with('success', 'Comentario añadido correctamente.');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'No se pudo añadir el comentario.'])->withInput();
        }
    }

    /**
     * Elimina un comentario específico.
     * Verifica que el usuario sea el autor del comentario o el dueño de la publicación.
     */
    public function destroy(Comment $comment): RedirectResponse
    {
        if (Auth::id() !== $comment->user_id && Auth::id() !== $comment->post->user_id) {
            abort(403);
        }

        $comment->delete();

        return back()->with('success', 'Comentario eliminado correctamente.');
    }
}
