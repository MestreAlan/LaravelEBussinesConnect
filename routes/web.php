<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use Illuminate\Http\Request;

// Rota para exibir a página de login
Route::get('/login', function () {
    return view('index');
})->name('login'); // Defina um nome para a rota

// Rotas dentro do grupo de middleware 'web' são protegidas pelo middleware CSRF automaticamente
Route::middleware('web')->group(function () {
    // Rota para processar a autenticação
    Route::post('/login', [IndexController::class, 'login'])->name('login.submit'); // Corrigido para usar o método de classe estático
});

Route::post('/logout', [IndexController::class, 'logout'])->name('logout');

Route::middleware('api')->group(function () {
    Route::middleware('jwt.auth')->get('/recurso-protegido', function () {
        return 'Este recurso está protegido por JWT.';
    });
});

Route::middleware('jwt.auth')->get('/verificar-autenticacao', function () {
    return response()->json(['message' => 'Usuário autenticado com sucesso.']);
});

// Rota de fallback para erros 404
Route::fallback(function(){
    return "Erro 404";
});
