<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    /**
     * Sigue a un usuario.
     */
    public function store(User $user): RedirectResponse
    {
        // No se puede seguir a uno mismo
        if (auth()->user()->id === $user->id) {
            return back()->withErrors(['error' => 'No puedes seguirte a ti mismo.']);
        }

        auth()->user()->following()->attach($user->id);

        return back();
    }

    /**
     * Deja de seguir a un usuario.
     */
    public function destroy(User $user): RedirectResponse
    {
        auth()->user()->following()->detach($user->id);

        return back();
    }
}