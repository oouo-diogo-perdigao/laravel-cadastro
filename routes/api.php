<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
	return $request->user();
});

use App\Http\Controllers\PersonagemController;

Route::get('personagem', [PersonagemController::class, 'index']);
Route::get('personagem/{id}', [PersonagemController::class, 'show']);
Route::put('personagem/{id}', [PersonagemController::class, 'update']);
Route::post('personagem', [PersonagemController::class, 'store']);
Route::delete('personagem/{id}', [PersonagemController::class, 'destroy']);

use App\Http\Controllers\LoginController;

Route::post('/login/google', [LoginController::class, 'google']);
Route::post('/logout', [LoginController::class, 'logout']);
