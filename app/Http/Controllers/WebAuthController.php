<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/***** WebAuthController: Se encarga de gestionar las llamadas de autenticación de la Web */
class WebAuthController extends AuthController
{
    /***** showLoginForm: Muestra el formulario de login en caso de no estar autenticado */
    public function showLoginForm()
    {
        // Si el usuario está autenticado, redirige a la página de dashboard
        if (auth()->check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /***** login: Se gestiona mediante variables de sesión y el uso de cookies para recordar al usuario */
    public function login(Request $request)
    {
        // Valida las credenciales y en caso de ser incorrectas devuelve los errores de validación
        $validation = $this->validateCredentials($request);
        if ($validation) {
            return redirect()->back()->withErrors($validation)->withInput();
        }

        // Intentar autenticar al usuario usando las credenciales (email y password)
        $credentials = $request->only('email', 'password');

        // Verificar si las credenciales son correctas y si está marcado remember guarda las cookies
        if (Auth::attempt($credentials, $request->has('remember'))) {
            // De momento sólo se permite el acceso al panel de control al usuario con rol de administrador. (403 Forbidden)
            if (!(auth()->check() && auth()->user()->role === 'admin')) {
                abort(403, 'No tienes permiso para acceder a esta página.');
            }
            return redirect()->route('dashboard');
        }

        // Si las credenciales son incorrectas devuelve un mensaje de error.
        return redirect()->back()->with('error', 'Wrong Credentials');
    }

    /***** logout: elimina la sesión del usuario y redirige a login */
    public function logout()
    {
        auth()->logout();
        // invalida ataques CSRF
        session()->regenerateToken();

        return redirect()->route('login');
    }
}