<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

/***** ApiAuthController: Se encarga de gestionar las llamadas de autenticación de la API */
class ApiAuthController extends AuthController
{
    /***** login: Devuelve los datos necesarios para la APP
     * Token de autenticación. Email y url_picture para rellenar el menú lateral de la APP
     */
    public function login(Request $request)
    {
        $validation = $this->validateCredentials($request);
        if ($validation) {
            return response()->json(['error' => $validation->errors()], 400);
        }

        // Llama a attempt de AuthController con los datos del $request
        $token = $this->attemptLogin($request);
        // Si no existe el token devuelve una respuesta con el código 401 (Unauthorized)
        if (!$token) {
            return response()->json(['error' => 'Unauthorized: Wrong email/password'], 401);
        }
        // Usa el token generado para autenticar al usuario y obtener sus datos
        $user = auth('api')->setToken($token)->user();
        
        // Si el token no pertenece a ningún suaurio devuelvo una respuesta con el código 500 (Server error)
        if (!$user) {
            return response()->json(['error' => 'Error retrieving authenticated user'], 500);
        }

        // Devuelve los datos necesarios para la APP con el código 200 (OK)
        return response()->json([
            'token' => $token,
            'email' => $user->email,
            'url_picture' => asset('') . $user->url_picture,
        ], 200);

    }

    /***** LOGOUT: invalida el token de autenticación */
    public function logout()
    {
        // Cierra la sesión del usuario
        auth()->logout();
        // Devuelve la respuesta OK con un mensaje.
        return response()->json(['message' => 'Successfully logged out'], 200);
    }
    
    /***** attemptLogin: comprueba las credenciales con la autenticación de JWT */
    protected function attemptLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // attempt es un método de JWT que devuelve un token de autenticación en caso de que las credenciales sean correctas
        if (!$token = JWTAuth::attempt($credentials)) {
            return null; // Login incorrecto
        }

        return $token; // Login correcto
    }

    /***** ME: Método para testear el token de autenticación */
    public function me()
    {
        return response()->json(auth('api')->user());
    }
}
