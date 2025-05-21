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
        $followingIds = Auth::user()->following()->pluck('users.id');
        $posts = Post::whereIn('user_id', $followingIds)
                     ->orWhere('user_id', Auth::id())
                     ->with(['user', 'likes', 'comments.user'])
                     ->latest()
                     ->get()
                     ->map(function ($post) {
                         $post->is_liked_by_auth_user = $post->likes->contains('user_id', Auth::id());
                         return [
                             'id' => $post->id,
                             'image_path' => $post->image_path,
                             'caption' => $post->caption,
                             'created_at' => $post->created_at->diffForHumans(),
                             'user' => [
                                 'id' => $post->user->id,
                                 'name' => $post->user->name,
                                 'avatar' => $post->user->avatar, // Incluye 'avatar' aquí también para posts de otros usuarios
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
            'user' => Auth::user()->only('id', 'name', 'email', 'avatar'), // ¡Esto es clave! Incluye 'avatar'
        ]);
    }
}