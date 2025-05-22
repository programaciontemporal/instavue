<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
    /**
     * Muestra los resultados de búsqueda de usuarios y publicaciones.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function results(Request $request)
    {
        $query = $request->input('query'); // Obtiene el término de búsqueda de la URL

        // Inicializa las colecciones vacías
        $users = collect();
        $posts = collect();

        if ($query) {
            // Búsqueda de usuarios: busca solo por el 'name' o 'email'
            // Ya que la columna 'username' no existe en tu tabla 'users'
            $users = User::where('name', 'like', '%' . $query . '%')
                         ->orWhere('email', 'like', '%' . $query . '%') // Opcional: buscar también por email
                         ->orderBy('name') // Ordenar por nombre
                         ->paginate(10, ['*'], 'users_page'); // Paginar usuarios, con nombre de página 'users_page'

            // Búsqueda de publicaciones: busca por título o descripción
            // Se usa eager loading para cargar el usuario, los likes y los comentarios de cada post
            $posts = Post::with(['user', 'likes', 'comments'])
                         ->where('caption', 'like', '%' . $query . '%')
                         ->orderByDesc('created_at')
                         ->paginate(10, ['*'], 'posts_page'); // Paginar publicaciones, con nombre de página 'posts_page'
        }

        return Inertia::render('Search/Results', [
            'query' => $query,
            'users' => $users,
            'posts' => $posts,
        ]);
    }
}
