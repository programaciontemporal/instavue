<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Renderiza el formulario de creación de publicaciones.
     */
    public function create(): Response
    {
        return Inertia::render('Posts/Create');
    }

    /**
     * Almacena una nueva publicación con imagen y descripción opcional.
     * Valida la imagen y redirige al perfil del usuario tras crear la publicación.
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'image' => ['required', 'image', 'max:2048'],
                'caption' => ['nullable', 'string', 'max:255'],
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        $imagePath = $request->file('image')->store('uploads', 'public');

        Auth::user()->posts()->create([
            'caption' => $request->caption,
            'image_path' => $imagePath,
        ]);

        return redirect()->route('profile.show', ['user' => auth()->user()->id])->with('success', 'Publicación creada con éxito.');
    }

    /**
     * Muestra una publicación específica con sus comentarios, likes y estado de like del usuario actual.
     */
    public function show(Post $post): Response
    {
        $post->load(['user', 'comments.user', 'likes']);
        $post->is_liked_by_auth_user = $post->likes->contains('user_id', auth()->id());

        return Inertia::render('Posts/Show', [
            'post' => $post,
            'authUserId' => auth()->id(),
        ]);
    }

    /**
     * Muestra el formulario de edición de una publicación.
     * Verifica que el usuario tenga permiso para editar la publicación.
     */
    public function edit(Post $post): Response
    {
        Gate::authorize('update', $post);
        $post->load('user');
        return Inertia::render('Posts/Edit', [
            'post' => $post,
        ]);
    }

    /**
     * Actualiza la descripción de una publicación existente.
     * Verifica los permisos del usuario y valida los datos.
     */
    public function update(Request $request, Post $post): RedirectResponse
    {
        Gate::authorize('update', $post);

        try {
            $request->validate([
                'caption' => ['nullable', 'string', 'max:255'],
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        $post->caption = $request->caption;
        $post->save();

        return redirect()->route('posts.show', $post->id)->with('success', 'Publicación actualizada con éxito.');
    }

    /**
     * Elimina una publicación y su imagen asociada del almacenamiento.
     * Verifica los permisos del usuario antes de proceder.
     */
    public function destroy(Post $post): RedirectResponse
    {
        Gate::authorize('delete', $post);

        if ($post->image_path) {
            Storage::disk('public')->delete($post->getRawOriginal('image_path'));
        }

        $post->delete();

        return redirect()->route('profile.show', auth()->user()->id)->with('success', 'Publicación eliminada con éxito.');
    }
}
