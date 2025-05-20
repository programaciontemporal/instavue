<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * Da 'me gusta' a una publicación.
     */
    public function store(Post $post): RedirectResponse
    {
        Auth::user()->likes()->create(['post_id' => $post->id]);

        return back();
    }

    /**
     * Quita el 'me gusta' de una publicación.
     */
    public function destroy(Post $post): RedirectResponse
    {
        Auth::user()->likes()->where('post_id', $post->id)->delete();

        return back();
    }
}