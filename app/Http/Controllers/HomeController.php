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
     * Muestra el feed principal del usuario autenticado.
     * Incluye publicaciones propias y de usuarios seguidos,
     * con información de likes, comentarios y estado de interacción.
     */
    public function index(): Response
    {
        $followingAndOwnIds = Auth::user()->following()->pluck('users.id')->push(Auth::id());

        $posts = Post::whereIn('user_id', $followingAndOwnIds)
                     ->with(['user', 'likes', 'comments.user'])
                     ->latest()
                     ->paginate(10);

        $posts->through(function ($post) {
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
            'user' => Auth::user()->only('id', 'name', 'email', 'avatar'),
        ]);
    }
}
