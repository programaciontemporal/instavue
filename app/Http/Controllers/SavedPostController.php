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
     * Display a listing of saved posts for the authenticated user.
     */
    public function index(): Response
    {
        // Obtener las publicaciones guardadas por el usuario autenticado, paginadas
        $savedPosts = Auth::user()->savedPosts()
                                ->with(['user', 'likes', 'comments.user'])
                                ->latest('saved_posts.created_at') // Ordenar por la fecha en que se guardó
                                ->paginate(10);

        // Mapear la colección paginada para preparar los datos para el frontend
        $savedPosts->through(function ($post) {
            $post->is_liked_by_auth_user = Auth::check() ? $post->likes->contains('user_id', Auth::id()) : false;
            // Asegurarse de que el post siempre tenga la propiedad is_saved_by_auth_user a true para esta vista
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
     * Save a post for the authenticated user.
     */
    public function store(Post $post): RedirectResponse
    {
        // Adjunta la publicación al usuario autenticado en la tabla pivote
        Auth::user()->savedPosts()->syncWithoutDetaching($post->id);

        return back()->with('success', 'Publicación guardada.');
    }

    /**
     * Unsave a post for the authenticated user.
     */
    public function destroy(Post $post): RedirectResponse
    {
        // Desvincula la publicación del usuario autenticado en la tabla pivote
        Auth::user()->savedPosts()->detach($post->id);

        return back()->with('success', 'Publicación eliminada de guardados.');
    }
}