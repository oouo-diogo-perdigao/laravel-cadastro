<?php

namespace App\Http\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;



class LoginController extends Controller
{
	public function google(Request $request)
	{
		$authHeader = $request->header('Authorization');
		if (!$authHeader || !preg_match('/^Bearer\s(\S+)$/', $authHeader, $matches)) {
			return response()->json(['error' => 'missing authorization header'], 401);
		}

		$token = $matches[1];
		list($header, $payload) = explode('.', $token);

		try {
			$header = json_decode(base64_decode($header), true);
			$payload = json_decode(base64_decode($payload), true);
		} catch (\Exception $e) {
			return response()->json(['error' => "can't parse the JWT's header"], 401);
		}

		$kid = $header['kid'] ?? null;
		if (!$kid) {
			return response()->json(['error' => "missing kid in JWT's header"], 401);
		}

		$user = User::where('email', $payload['email'])->first();

		if ($user) {
			$newPayload = [
				'iss' => $request->getHost(),
				'exp' => time() + 60 * 60 * 24 * 7,
				'email' => $payload['email']
			];
			$jwtSend = JWT::encode($newPayload, env('KEY_PRIVATE'), 'RS256');

			$user->update(['last_login' => now()]);

			return response()->json($jwtSend, 200);
		} else {
			// Create new user
			$newPayload = [
				'iss' => $request->getHost(),
				'exp' => time() + 60 * 60 * 24 * 7,
				'email' => $payload['email']
			];

			$user = User::create([
				'email' => $payload['email'],
				'nick' => $payload['given_name'] ?? '',
				'created_at' => now(),
				'last_login' => now(),
				'name' => $payload['name'] ?? '',
				'language' => 'pt-br',
				'avatar' => $payload['picture'] ?? ''
			]);

			$jwtSend = JWT::encode($newPayload, env('KEY_PRIVATE'), 'RS256');

			return response()->json($jwtSend, 201);
		}
	}



	public function logout(Request $request)
	{
		return response()->json(['error' => 'not implemented'], 501);
	}
}

