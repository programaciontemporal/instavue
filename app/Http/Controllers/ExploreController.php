<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;

class ExploreController extends Controller
{
    /**
     * Display the explore page with all posts.
     */
    public function index(): Response
    {
        $posts = Post::with(['user', 'likes', 'comments.user']) // Cargar relaciones necesarias
                     ->latest()
                     ->get()
                     ->map(function ($post) {
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
                                 'avatar' => $post->user->avatar, // Asegúrate de que el modelo User tenga un campo 'avatar'
                             ],
                             'likes' => $post->likes->map(fn($like) => ['user_id' => $like->user_id]), // Solo el ID del usuario para el frontend
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

        return Inertia::render('Explore', [
            'posts' => $posts,
            'authUserId' => Auth::id(), // También pasamos el ID del usuario autenticado
        ]);
    }
}
