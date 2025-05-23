<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Controlador para gestionar la autenticación de sesiones de usuario
 *
 * Este controlador maneja las operaciones relacionadas con la autenticación
 * de usuarios, incluyendo el inicio y cierre de sesión.
 */
class AuthenticatedSessionController extends Controller
{
    // El método create ha sido eliminado ya que la ruta 'auth.combined'
    // ahora maneja directamente el renderizado a través de LoginRegisterSlider.vue

    /**
     * Procesa una solicitud de autenticación entrante
     *
     * @param LoginRequest $request La solicitud de inicio de sesión validada
     * @return RedirectResponse Redirección al perfil del usuario si la autenticación es exitosa
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->route('profile.show', auth()->user());
    }

    /**
     * Cierra la sesión del usuario actual
     *
     * Este método realiza las siguientes acciones:
     * - Cierra la sesión del usuario
     * - Invalida la sesión actual
     * - Regenera el token CSRF
     * - Redirecciona al usuario a la página principal
     *
     * @param Request $request La solicitud HTTP
     * @return RedirectResponse Redirección a la página principal
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
