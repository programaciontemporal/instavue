<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Controlador para gestionar el registro de nuevos usuarios
 *
 * Este controlador se encarga de manejar el proceso de registro de nuevos usuarios
 * en la aplicación, incluyendo la validación de datos y la creación de la cuenta.
 */
class RegisteredUserController extends Controller
{
    // El método create ha sido eliminado ya que la ruta 'auth.combined'
    // ahora maneja directamente el renderizado a través de LoginRegisterSlider.vue

    /**
     * Procesa una solicitud de registro de nuevo usuario
     *
     * Este método realiza las siguientes acciones:
     * - Valida los datos de entrada del usuario
     * - Crea un nuevo registro de usuario en la base de datos
     * - Dispara el evento Registered
     * - Inicia sesión automáticamente con el nuevo usuario
     * - Redirecciona al perfil del usuario
     *
     * @param Request $request La solicitud HTTP con los datos del nuevo usuario
     * @return RedirectResponse Redirección al perfil del usuario
     * @throws \Illuminate\Validation\ValidationException Si la validación falla
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('profile.show', $user->id);
    }
}
