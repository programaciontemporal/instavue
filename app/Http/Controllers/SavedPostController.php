<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;

class SavedPostController extends Controller
{
    /**
     * Muestra el listado de publicaciones guardadas por el usuario autenticado.
     * Las publicaciones se cargan con sus relaciones y se ordenan por fecha de guardado.
     * Incluye información de likes, comentarios y estado de guardado.
     */
    public function index(): Response
    {
        $savedPosts = Auth::user()->savedPosts()
                                ->with(['user', 'likes', 'comments.user'])
                                ->latest('saved_posts.created_at')
                                ->paginate(10);

        $savedPosts->through(function ($post) {
            $post->is_liked_by_auth_user = Auth::check() ? $post->likes->contains('user_id', Auth::id()) : false;
            $post->is_saved_by_auth_user = true;

            return [
                'id' => $post->id,
                'image_path' => $post->image_path,
                'caption' => $post->caption,
                'created_at' => $post->created_at->diffForHumans(),
                'user' => [
                    'id' => $post->user->id,
                    'name' => $post->user->name,
                    'avatar' => $post->user->avatar,
                ],
                'likes' => $post->likes->map(fn($like) => ['user_id' => $like->user_id]),
                'likes_count' => $post->likes->count(),
                'comments' => $post->comments->map(function ($comment) {
                    return [
                        'id' => $comment->id,
                        'body' => $comment->body,
                        'user' => [
                            'id' => $comment->user->id,
                            'name' => $comment->user->name,
                        ],
                        'created_at' => $comment->created_at->diffForHumans(),
                    ];
                }),
                'comments_count' => $post->comments->count(),
                'is_liked_by_auth_user' => $post->is_liked_by_auth_user,
                'is_saved_by_auth_user' => $post->is_saved_by_auth_user,
            ];
        });

        return Inertia::render('SavedPosts/Index', [
            'posts' => $savedPosts,
            'authUserId' => Auth::id(),
            'user' => Auth::user()->only('id', 'name', 'email', 'avatar'),
        ]);
    }

    /**
     * Guarda una publicación en la colección del usuario autenticado.
     * Utiliza syncWithoutDetaching para evitar duplicados.
     */
    public function store(Post $post): RedirectResponse
    {
        Auth::user()->savedPosts()->syncWithoutDetaching($post->id);
        return back()->with('success', 'Publicación guardada.');
    }

    /**
     * Elimina una publicación de la colección de guardados del usuario.
     */
    public function destroy(Post $post): RedirectResponse
    {
        Auth::user()->savedPosts()->detach($post->id);
        return back()->with('success', 'Publicación eliminada de guardados.');
    }
}
