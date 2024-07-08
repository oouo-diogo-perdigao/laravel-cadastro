<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonagemController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\EnderecoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('/login/google', [LoginController::class, 'google']);
Route::post('/logout', [LoginController::class, 'logout']);


// Rotas protegidas pelo middleware auth:sanctum
Route::middleware('auth.google')->group(function () {
	Route::get('/user', function (Request $request) {
		return $request->user();
	});

	Route::get('personagem', [PersonagemController::class, 'index']);
	Route::post('personagem', [PersonagemController::class, 'store']);
	Route::get('personagem/{id}', [PersonagemController::class, 'show']);
	Route::put('personagem/{id}', [PersonagemController::class, 'update']);
	Route::delete('personagem/{id}', [PersonagemController::class, 'destroy']);

	Route::get('buscar-cep/{cep}', [EnderecoController::class, 'buscarCep']);
});
