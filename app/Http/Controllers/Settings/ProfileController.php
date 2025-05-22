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
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Support\Facades\Redirect; // Añadir esta importación si no está

class ProfileController extends Controller
{
    /**
     * Display the user's profile settings form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user()->hasVerifiedEmail(),
            'status' => session('status'),
            'user' => $request->user()->only('id', 'name', 'email', 'avatar'),
        ]);
    }

    /**
     * Update the user's profile information and avatar.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        if ($request->hasFile('avatar')) {
            if ($user->avatar && strpos($user->getRawOriginal('avatar'), 'uploads/avatars/') !== false) {
                Storage::disk('public')->delete($user->getRawOriginal('avatar'));
            }
            $avatarPath = $request->file('avatar')->store('uploads/avatars', 'public');
            $user->avatar = $avatarPath;
        }

        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('success', 'Perfil actualizado con éxito.');
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

        if ($user->avatar && strpos($user->getRawOriginal('avatar'), 'uploads/avatars/') !== false) {
            Storage::disk('public')->delete($user->getRawOriginal('avatar'));
        }

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
                      ->with(['user', 'likes', 'comments.user'])
                      ->latest()
                      ->paginate(12);

        // Mapear la colección paginada para preparar los datos para el frontend
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
                'comments_count' => $post->comments->count(), // ¡Ahora sí corregido DEFINITIVAMENTE!
                'is_liked_by_auth_user' => $post->is_liked_by_auth_user,
            ];
        });

        // Determinar si el usuario autenticado está siguiendo a este perfil
        // Si el perfil que se está viendo es el propio del usuario autenticado, no puede seguirse a sí mismo
        $isFollowingAuthUser = false;
        if (Auth::check() && Auth::id() !== $user->id) {
            $isFollowingAuthUser = Auth::user()->isFollowing($user);
        }

        return Inertia::render('Profile/Show', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'bio' => $user->bio ?? null, // Asumiendo que User tiene un campo 'bio'
                'posts_count' => $user->posts()->count(),
                'followers_count' => $user->followers()->count(),
                'following_count' => $user->following()->count(),
                'is_following_auth_user' => $isFollowingAuthUser, // Este será el prop para el frontend
            ],
            'posts' => $posts, // Pasa la colección paginada de posts
            'authUserId' => Auth::id(), // Pasa el ID del usuario autenticado
        ]);
    }
}
