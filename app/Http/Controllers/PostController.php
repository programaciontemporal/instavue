<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class PostController extends Controller
{
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
                'image' => ['required', 'image', 'max:2048'], // Máximo 2MB
                'caption' => ['nullable', 'string', 'max:255'],
            ]);
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        $imagePath = $request->file('image')->store('uploads', 'public'); // Guarda la imagen en storage/app/public/uploads

        Auth()->user()->posts()->create([
            'caption' => $request->caption,
            'image_path' => $imagePath,
        ]);

        return redirect()->route('profile.show', ['user' => auth()->user()->id]);
    }
}