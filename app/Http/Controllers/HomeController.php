<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Muestra el feed principal de la aplicación con publicaciones de usuarios seguidos.
     */
    public function index(): Response
    {
        $followingIds = Auth::user()->following()->pluck('following_user_id');

        $posts = Post::whereIn('user_id', $followingIds)
            ->with(['user', 'comments.user', 'likes']) // Asegura que los likes se carguen
            ->latest()
            ->get();

        // Para cada post, verifica si el usuario autenticado ya le dio "Me gusta"
        $posts->each(function ($post) {
            $post->is_liked_by_auth_user = $post->likes->contains('user_id', Auth::id());
        });

        return Inertia::render('Home', [
            'posts' => $posts,
            'authUserId' => Auth::id(), // Pasar el ID del usuario autenticado para comparaciones en el frontend
        ]);
    }
}