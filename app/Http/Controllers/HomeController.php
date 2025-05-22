<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Display the user's feed.
     */
    public function index(): Response
    {
        // Obtener los IDs de los usuarios a los que sigue el usuario autenticado
        $followingIds = Auth::user()->following()->pluck('users.id');

        // Obtener las publicaciones de los usuarios seguidos y del propio usuario autenticado,
        // incluyendo las relaciones 'user', 'likes' y 'comments.user'.
        // Se aplica paginación con 10 posts por página.
        $posts = Post::whereIn('user_id', $followingIds)
                     ->orWhere('user_id', Auth::id())
                     ->with(['user', 'likes', 'comments.user'])
                     ->latest()
                     ->paginate(10); // <-- Cambiado de get() a paginate(10)

        // Mapear la colección paginada para preparar los datos para el frontend
        $posts->through(function ($post) {
            // Añadir si el usuario autenticado ha dado like
            $post->is_liked_by_auth_user = Auth::check() ? $post->likes->contains('user_id', Auth::id()) : false;
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
                // Solo el ID del usuario para el frontend
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
            ];
        });


        return Inertia::render('Home', [
            'posts' => $posts, // Pasa la colección paginada de posts
            'authUserId' => Auth::id(),
            'user' => Auth::user()->only('id', 'name', 'email', 'avatar'),
        ]);
    }
}
