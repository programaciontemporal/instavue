<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SearchController extends Controller
{
    /**
     * Procesa y muestra los resultados de búsqueda de usuarios y publicaciones.
     * Permite buscar usuarios por nombre y email, y publicaciones por su descripción.
     * Los resultados se paginan en grupos de 10 elementos.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function results(Request $request)
    {
        $query = $request->input('query');

        $users = collect();
        $posts = collect();

        if ($query) {
            $users = User::where('name', 'like', '%' . $query . '%')
                         ->orWhere('email', 'like', '%' . $query . '%')
                         ->orderBy('name')
                         ->paginate(10, ['*'], 'users_page');

            $posts = Post::with(['user', 'likes', 'comments'])
                         ->where('caption', 'like', '%' . $query . '%')
                         ->orderByDesc('created_at')
                         ->paginate(10, ['*'], 'posts_page');
        }

        return Inertia::render('Search/Results', [
            'query' => $query,
            'users' => $users,
            'posts' => $posts,
        ]);
    }
}
