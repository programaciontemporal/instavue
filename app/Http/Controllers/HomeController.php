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
        // Obtener las publicaciones de los usuarios que el usuario autenticado sigue
        // y también las publicaciones del propio usuario autenticado
        // Se especifica 'users.id' para evitar la ambigüedad en la consulta
        $followingIds = Auth::user()->following()->pluck('users.id');
        $posts = Post::whereIn('user_id', $followingIds)
                     ->orWhere('user_id', Auth::id()) // Incluir posts del propio usuario
                     ->with(['user', 'likes', 'comments.user']) // Cargar relaciones necesarias
                     ->latest()
                     ->get()
                     ->map(function ($post) {
                         // Añadir si el usuario autenticado ha dado like
                         $post->is_liked_by_auth_user = $post->likes->contains('user_id', Auth::id());
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
                         ];
                     });

        return Inertia::render('Home', [
            'posts' => $posts,
            'authUserId' => Auth::id(),
        ]);
    }
}
