<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyGoogleJWT
{
	public function handle(Request $request, Closure $next)
	{
		$Authorization = $request->header('Authorization');

		if (!$Authorization) {
			return response()->json(['error' => 'Missing or invalid Authorization header'], 401);
		}

		try {
			$decoded = JWT::decode($Authorization, new Key(env('KEY_PUBLIC'), 'RS256'));
		} catch (\Exception $e) {
			return response()->json(['error' => 'Invalid JWT token'], 401);
		}

		// $user = User::where('email', $decoded->email)->first();

		// if (!$user) {
		// 	return response()->json(['error' => 'User not found'], 404);
		// }

		// // Adiciona o usuário autenticado à requisição
		// $request->user = $user;

		$response = $next($request);

		return $response;
	}
}
