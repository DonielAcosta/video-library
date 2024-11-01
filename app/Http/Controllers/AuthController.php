<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller{
    public function register(Request $request){
        // Validar los datos de entrada
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,user', // Asegúrate de que el rol sea válido
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Crear el usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role, // Asignar el rol
        ]);

        // Generar un token
        $token = $user->createToken($request->name)->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Usuario registrado con éxito',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ], 201);
    }

    
}
