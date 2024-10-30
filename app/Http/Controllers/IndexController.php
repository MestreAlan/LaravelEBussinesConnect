<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class IndexController extends Controller
{
    public function login(Request $request)
    {
        // Obter login e senha dos dados da requisição
        $login = $request->input('login');
        $password = $request->input('password');

        // Criar um array de credenciais para autenticação
        $credentials = [
            'login' => $login,
            'password' => $password,
            'active' => true,
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Emitir um token JWT para o usuário autenticado
            $token = JWTAuth::fromUser($user);

            return response()->json(['feedback' => 1, 'token' => $token]);
        }

        return response()->json(['feedback' => 0]);
    }

    public function logout()
    {
        return response()->json(['redirect' => route('login')]);
    }
}
