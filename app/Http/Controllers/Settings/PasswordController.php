<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Controlador para gestionar la configuración de contraseña del usuario.
 */
class PasswordController extends Controller
{
    /**
     * Muestra la página de configuración de contraseña del usuario.
     *
     * @return Response Renderiza la vista de configuración de contraseña
     */
    public function edit(): Response
    {
        return Inertia::render('settings/Password');
    }

    /**
     * Actualiza la contraseña del usuario.
     *
     * @param Request $request La solicitud HTTP con los datos de la contraseña
     * @return RedirectResponse Redirecciona de vuelta a la página anterior
     *
     * El método valida:
     * - La contraseña actual del usuario
     * - La nueva contraseña que debe cumplir con los requisitos predeterminados
     * - La confirmación de la nueva contraseña
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back();
    }
}
