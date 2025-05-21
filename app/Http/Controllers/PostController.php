<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Gate; // <-- Asegúrate de que esta línea esté presente
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    // ELIMINA COMPLETAMENTE EL MÉTODO __construct() de aquí.

    /**
     * Muestra el formulario para crear un nuevo post.
     */
    public function create(): Response
    {
        return Inertia::render('Posts/Create');
    }

    /**
     * Almacena un nuevo post en la base de datos.
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
     * Muestra un post específico.
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
     * Muestra el formulario para editar un post.
     */
    public function edit(Post $post): Response
    {
        Gate::authorize('update', $post); // <-- Volvemos a añadir esta línea
        return Inertia::render('Posts/Edit', [
            'post' => $post,
        ]);
    }

    /**
     * Actualiza un post existente en la base de datos.
     */
    public function update(Request $request, Post $post): RedirectResponse
    {
        Gate::authorize('update', $post); // <-- Volvemos a añadir esta línea

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
     * Elimina un post de la base de datos.
     */
    public function destroy(Post $post): RedirectResponse
    {
        Gate::authorize('delete', $post); // <-- Volvemos a añadir esta línea

        if ($post->image_path) {
            Storage::disk('public')->delete($post->getRawOriginal('image_path'));
        }

        $post->delete();

        return redirect()->route('profile.show', auth()->user()->id)->with('success', 'Publicación eliminada con éxito.');
    }
}
