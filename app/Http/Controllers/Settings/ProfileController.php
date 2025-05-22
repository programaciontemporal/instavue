<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user()->hasVerifiedEmail(),
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->user()->fill($request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($request->user()->id)],
        ]));

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return back();
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('welcome');
    }

    /**
     * Display a specific user's profile.
     */
    public function show(User $user): Response
    {
        // Cargar las publicaciones del usuario con paginación y relaciones necesarias
        $posts = $user->posts()
                      ->with(['user', 'likes', 'comments.user']) // Eager load relationships
                      ->latest()
                      ->paginate(12); // Paginación: 12 posts por página

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
                    'avatar' => $post->user->avatar, // Asegúrate de que el modelo User tenga un campo 'avatar'
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

        // Contadores de posts, seguidores y seguidos (ya estaban bien)
        $postsCount = $user->posts()->count();
        $followersCount = $user->followers()->count();
        $followingCount = $user->following()->count();

        // Verificar si el usuario autenticado está siguiendo a este perfil
        $isFollowing = Auth::check() ? Auth::user()->isFollowing($user) : false;

        return Inertia::render('Profile/Show', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar,
            ],
            'posts' => $posts, // Pasa la colección paginada de posts
            'postsCount' => $postsCount,
            'followersCount' => $followersCount,
            'followingCount' => $followingCount,
            'isFollowing' => $isFollowing,
            'canEdit' => Auth::check() && Auth::user()->id === $user->id,
            'authUserId' => Auth::id(), // Pasar el ID del usuario autenticado
        ]);
    }
}
