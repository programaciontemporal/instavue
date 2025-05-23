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
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
    /**
     * Muestra el formulario de edición del perfil del usuario.
     * Incluye información del estado de verificación de email y datos del usuario.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user()->hasVerifiedEmail(),
            'status' => session('status'),
            'user' => $request->user()->append('avatar_url')->only('id', 'name', 'email', 'avatar', 'bio', 'avatar_url'),
        ]);
    }

    /**
     * Actualiza la información del perfil del usuario.
     * Maneja la actualización del avatar y la verificación del email.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if ($request->boolean('remove_avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = null;
        } elseif ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Elimina la cuenta del usuario y todos sus datos asociados.
     * Incluye la eliminación del avatar y la invalidación de la sesión.
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
     * Muestra el perfil público de un usuario.
     * Incluye sus publicaciones, estadísticas de seguidores y estado de seguimiento.
     */
    public function show(User $user): Response
    {
        $user->append('avatar_url');

        $posts = $user->posts()
                      ->with(['user', 'likes', 'comments.user'])
                      ->latest()
                      ->paginate(12);

        $posts->through(function ($post) {
            $post->user->append('avatar_url');

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
                    'avatar_url' => $post->user->avatar_url, // Envía 'avatar_url' explícitamente
                ],
                'likes' => $post->likes->map(fn($like) => ['user_id' => $like->user_id]),
                'likes_count' => $post->likes->count(),
                'comments' => $post->comments->map(function ($comment) {
                    $comment->user->append('avatar_url');
                    return [
                        'id' => $comment->id,
                        'body' => $comment->body,
                        'user' => [
                            'id' => $comment->user->id,
                            'name' => $comment->user->name,
                            'avatar_url' => $comment->user->avatar_url, // Envía 'avatar_url' explícitamente
                        ],
                        'created_at' => $comment->created_at->diffForHumans(),
                    ];
                }),
                'comments_count' => $post->comments->count(),
                'is_liked_by_auth_user' => $post->is_liked_by_auth_user,
            ];
        });

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
                'bio' => $user->bio ?? null,
                'posts_count' => $user->posts()->count(),
                'followers_count' => $user->followers()->count(),
                'following_count' => $user->following()->count(),
                'is_following_auth_user' => $isFollowingAuthUser,
                'avatar_url' => $user->avatar_url, // Envía 'avatar_url' explícitamente para el usuario principal
            ],
            'posts' => $posts,
            'authUserId' => Auth::id(),
        ]);
    }
}
