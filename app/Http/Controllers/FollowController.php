<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    /**
     * Establece una relación de seguimiento con otro usuario.
     * Verifica que el usuario no intente seguirse a sí mismo.
     */
    public function store(User $user): RedirectResponse
    {
        if (auth()->user()->id === $user->id) {
            return back()->withErrors(['error' => 'No puedes seguirte a ti mismo.']);
        }

        auth()->user()->following()->attach($user->id);
        return back();
    }

    /**
     * Elimina la relación de seguimiento con un usuario.
     */
    public function destroy(User $user): RedirectResponse
    {
        auth()->user()->following()->detach($user->id);
        return back();
    }
}
