<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // REGISTO (Cria o utilizador e dá o Token)
    public function register(Request $request)
    {
        // Cria o utilizador diretamente no MySQL
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
        ]);

        // Devolve os dados e o Token
        return response()->json([
            'user' => $user,
            'token' => $user->createToken('auth_token')->plainTextToken
        ], 201);
    }

    // LOGIN (Valida e dá o Token)
    public function login(Request $request)
    {
        // faz o SELECT e valida a password aqui
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Dados inválidos'], 401);
        }

        // Se passou o IF, vai buscar o utilizador que acabou de logar e gera o Token
        $user = Auth::user();
        
        return response()->json([
            'user' => $user,
            'token' => $user->createToken('auth_token')->plainTextToken
        ]);
    }

    // LOGOUT (Apaga o Token atual)
    public function logout()
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();
        
        return response()->json(['message' => 'Deslogado']);
    }
}