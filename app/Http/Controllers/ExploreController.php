<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class ExploreController extends Controller
{
    /**
     * Muestra la página de explorar con publicaciones de usuarios no seguidos.
     */
    public function index(): Response
    {
        $user = Auth::user();

        // Obtener IDs de usuarios que el usuario actual sigue o que son el propio usuario
        $followingAndSelfIds = $user->following()->pluck('following_user_id')->push($user->id);

        // Obtener publicaciones de usuarios que NO están en la lista anterior
        $posts = Post::whereNotIn('user_id', $followingAndSelfIds)
            ->with(['user', 'likes', 'comments']) // Eager load user, likes y comments
            ->latest()
            ->limit(30) // Limitar el número de posts para empezar
            ->get();

        // Puedes añadir aquí lógica para ordenar por popularidad si lo deseas más adelante
        // Por ahora, es por fecha (latest())

        return Inertia::render('Explore', [
            'posts' => $posts,
        ]);
    }
}