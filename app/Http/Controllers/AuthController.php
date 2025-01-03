<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/***** AuthController: Clase abstacta con métodos comunes para la autenticación,
 * de la que heredan WebAuthController y ApiController */
abstract class AuthController extends Controller
{
    /***** validateCredentials: Valida las credenciales del usuario (email, password) */
    protected function validateCredentials(Request $request)
    {   
        // Ambos son requeridos, email debe tener un formato correcto y password debe ser como mínimo de 6 caracteres
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return $validator;
        }

        return null;
    }
    
}

