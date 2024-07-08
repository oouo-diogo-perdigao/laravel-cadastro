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
	private $public_key = "-----BEGIN PUBLIC KEY-----
MIIBITANBgkqhkiG9w0BAQEFAAOCAQ4AMIIBCQKCAQB7dzRms1i0D9Lrxc3knz/Z
qeU4FxCXPTl30szwL/DF+VnY2D9lFOcqEU2PFQBCpSnkWQ8OLZ4n5BBnkCQGDXai
shXu03/ndnQw7gyZ6C5ROCo5WLQRFkO4TzSj4SH3ZS2ydADDX31RMg6FIRspzQji
s1TFm/RRoMXVyuWPNZ4CP4H1dSrNj+1UzRAtJiiq5Y8x8GyaHI78k6xUI2IjnkmO
gWOehfXzDqlIDdpASO77G/sRRp9IDlVwfI4KLzwFG1S3vNFgmYUxe5KOSNgrgNRT
9h2BgJTP/y5SEft8OM1n8GwEA4Cv2Jx9ypJ+myTaItIN35496EYKtP69IOUt93Mj
AgMBAAE=
-----END PUBLIC KEY-----";

	public function handle(Request $request, Closure $next)
	{
		$Authorization = $request->header('Authorization');

		if (!$Authorization) {
			return response()->json(['error' => 'Missing or invalid Authorization header'], 401);
		}

		try {
			// Decodificar o token JWT
			$decoded = JWT::decode($Authorization, new Key($this->public_key/*env('KEY_PUBLIC')*/ , 'RS256'));
		} catch (\Exception $e) {
			return response()->json(['error' => $decoded /*'Invalid JWT token'*/], 401);
		}

		$user = User::where('email', $decoded->email)->first();
		if (!$user) {
			return response()->json(['error' => 'User not found'], 404);
		}
		// Adiciona o usuário autenticado à requisição
		$request->user = $user;
		$response = $next($request);

		return $response;
	}
}
