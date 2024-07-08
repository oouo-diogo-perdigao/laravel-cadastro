<?php

namespace App\Http\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;


class LoginController extends Controller
{
	private $private_key = "-----BEGIN RSA PRIVATE KEY-----
MIIEoQIBAAKCAQB7dzRms1i0D9Lrxc3knz/ZqeU4FxCXPTl30szwL/DF+VnY2D9l
FOcqEU2PFQBCpSnkWQ8OLZ4n5BBnkCQGDXaishXu03/ndnQw7gyZ6C5ROCo5WLQR
FkO4TzSj4SH3ZS2ydADDX31RMg6FIRspzQjis1TFm/RRoMXVyuWPNZ4CP4H1dSrN
j+1UzRAtJiiq5Y8x8GyaHI78k6xUI2IjnkmOgWOehfXzDqlIDdpASO77G/sRRp9I
DlVwfI4KLzwFG1S3vNFgmYUxe5KOSNgrgNRT9h2BgJTP/y5SEft8OM1n8GwEA4Cv
2Jx9ypJ+myTaItIN35496EYKtP69IOUt93MjAgMBAAECggEAdKyVjGf3b1F01PG8
PExxnlP7FSJWv1VQNXezy1bChZO54D2ayv8R5KMtVAVYlaJXED9JBvD9AF15UyTX
phCbB+ya5XJg8G70JjkPF3JeMsqMvFyi7XLI1CelayiZXJz0risJfZeDaXOQXSaH
8NydhP5LIeLa4zi3O+3dOZRFmYDuD7vzwu/Z6VSWvN8AytNqUiQlCzIF/joDDQng
8bAtux/+26u7rRIMFNgaUBtVZ+q3dVGkwnyNKKOkFqZP9QQb+1umoHkQzhn1cTTA
1lZqwRQU6xs6kjljQA1ZBLXG/DOEdpI4WTv9tFt+5NaKc6YJY37srDdwbfSeJFCz
xg0T4QKBgQDQofC32T9lbJFPWPmfVP+UO5Irak0WsWhW65HJH7bugqL+TrCNmJqi
c86RZ8e3OHEpfOvoB6/HPeKJ71sk9kWdE6aZcFzJhLr35514a6u+94SwL/YaUzsJ
wr5zFawIRdN5fBIAgulrwcAoNli7d1SeOKI8icHydTxJtFxeHV8KrQKBgQCXfznE
DzvkgrxbQLbWtVVRO1KJA6r1E6Fy+cTQCwZ9+DgJTNBNHjpzGZuQ1cURdCEGUtuK
zorFAPqfaw8wdRUbB9bqyf6of55yOm1UOo2Aic0mnxHJtqtaE0imGqmfpFObXsuG
pIZ3e7NudswlH5uz2fefdnW85r/nPrUNGMJ/DwKBgDoWjJYzp6IOm1qzVDUXjl1w
RJ9P5ozF8l4ZEqsj6GP5/XW+5QtXjN5kTgRQF9wcm+1IOcqNsVRRF6z99quQ4gr8
+KrKDRuSmBgD5eUsXVI7Sjdf8y6bM9ng3fPe+doHzvyJHd4ElQcKl/zaPNRdQjMX
Y5xlYpmmKJYA4KLXnKm1AoGAIQtwNzK3dq2JlkH0X7rn5DMTWq2BIbnpcmHGqzZs
cReuaWDm4ptgVnDUfPAT7y2scmuqVYmdvUDsac95XbF8LAw2tbnfoTNNBU93P3MS
IGJubCkwQRZmI9ym8QxJyXeXgQFZVYIXjoJd7g9dkg9l8AHwQEkff1e5riAAb93h
tsMCgYB4rQ/4nweCjqt+xRjKUkwybEZa5m7++4qxXcs+tb/oWietNh9nSzH+hKPe
oLs95GvYEGBnKZWA9AuNGQIWUJbZjr2SFwzHfCUi5QgnSk6NfvEbmPS30Dbr5Wrw
90o01P9Z08YFIPgyuxye6R2YihUl6bTJwWk9fKyIY2eqoLseWw==
-----END RSA PRIVATE KEY-----";

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

		$user = User::where('email', $payload['email'])->first();

		if ($user) {
			$newPayload = [
				'iss' => $request->getHost(),
				'exp' => time() + 60 * 60 * 24 * 7,
				'email' => $payload['email']
			];

			$jwtSend = JWT::encode($newPayload, $this->private_key/*env('KEY_PRIVATE')*/ , 'RS256');
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

			$jwtSend = JWT::encode($newPayload, $this->private_key/*env('KEY_PRIVATE')*/ , 'RS256');

			return response()->json($jwtSend, 402);
		}
	}



	public function logout(Request $request)
	{
		return response()->json(['error' => 'not implemented'], 501);
	}
}

