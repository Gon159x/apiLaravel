<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User; // importa el modelo User

use Illuminate\Support\Facades\Hash;


class ApiController extends Controller
{
    public function register(Request $request){
    // Valida los datos recibidos del formulario
    $validatedData = $request->validate([
        'name' => 'required|max:255',
        'email' => 'required|email|unique:users|max:255',
        'password' => 'required|min:6|confirmed',
    ]);
    
    // Crea el usuario
    $user = User::create([
        'name' => $validatedData['name'],
        'email' => $validatedData['email'],
        'password' => bcrypt($validatedData['password']),
    ]);
    
    // Genera un token para el usuario
    $token = $user->createToken('my-app-token')->plainTextToken;
    
    // Devuelve la respuesta JSON con el token y el usuario registrado
    return response()->json([
        'access_token' => $token,
        'token_type' => 'Bearer',
        'user' => $user
    ]);
    }

    public function login(Request $request)
{
    // Valida los datos recibidos del formulario
    $validatedData = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Busca al usuario en la base de datos por su correo electrónico
    $user = User::where('email', $validatedData['email'])->first();

    // Si el usuario no existe o la contraseña es incorrecta, devuelve una respuesta de error
    if (! $user || ! Hash::check($validatedData['password'], $user->password)) {
        return response()->json(['message' => 'Credenciales inválidas'], 401);
    }

    // Genera un nuevo token de autenticación para el usuario
    $token = $user->createToken('my-app-token')->plainTextToken;

    // Devuelve una respuesta JSON con el token y la información del usuario
    return response()->json([
        'access_token' => $token,
        'token_type' => 'Bearer',
        'user' => $user
    ]);
}


}
