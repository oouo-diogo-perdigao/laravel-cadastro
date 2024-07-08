# Backend do Projeto de CRUD de Personagens

Este projeto é a API backend para um aplicativo de CRUD de personagens, construído usando Laravel. A API permite adicionar, visualizar, editar e excluir personagens, além de buscar informações baseadas no CEP.

## Funcionalidades

- API para operações CRUD de personagens.
- Upload de imagem para o personagem e armazenamento no Amazon S3.
- Busca de informações de endereço usando o serviço ViaCEP.

## Tecnologias Utilizadas

- Laravel
- MySQL
- Amazon S3
- ViaCEP
## Instalação

1. Clone o repositório:
2. Instale as dependências: `composer install`
3. Configure o arquivo /.env:
	```.env
	APP_NAME=Laravel
	APP_ENV=local
	APP_KEY=base64:25HkfUgFP0H0O1ZyjQSnvcJublDhauy1C5fLXbumux4=
	APP_DEBUG=true
	APP_URL=http://localhost

	LOG_CHANNEL=stack
	LOG_DEPRECATIONS_CHANNEL=null
	LOG_LEVEL=debug

	DB_CONNECTION=mysql
	DB_HOST=127.0.0.1
	DB_PORT=3306
	DB_DATABASE=intriga-travelrpg
	DB_USERNAME=root
	DB_PASSWORD=

	BROADCAST_DRIVER=log
	CACHE_DRIVER=file
	FILESYSTEM_DISK=local
	QUEUE_CONNECTION=sync
	SESSION_DRIVER=file
	SESSION_LIFETIME=120

	MEMCACHED_HOST=127.0.0.1

	REDIS_HOST=127.0.0.1
	REDIS_PASSWORD=null
	REDIS_PORT=6379

	MAIL_MAILER=smtp
	MAIL_HOST=mailpit
	MAIL_PORT=1025
	MAIL_USERNAME=null
	MAIL_PASSWORD=null
	MAIL_ENCRYPTION=null
	MAIL_FROM_ADDRESS="hello@example.com"
	MAIL_FROM_NAME="${APP_NAME}"

	PUSHER_APP_ID=
	PUSHER_APP_KEY=
	PUSHER_APP_SECRET=
	PUSHER_HOST=
	PUSHER_PORT=443
	PUSHER_SCHEME=https
	PUSHER_APP_CLUSTER=mt1

	VITE_APP_NAME="${APP_NAME}"
	VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
	VITE_PUSHER_HOST="${PUSHER_HOST}"
	VITE_PUSHER_PORT="${PUSHER_PORT}"
	VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
	VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

	AWS_ACCESS_KEY_ID= -- preencher --
	AWS_SECRET_ACCESS_KEY= -- preencher --
	AWS_DEFAULT_REGION= -- preencher --
	AWS_BUCKET= -- preencher --
	AWS_URL= -- preencher --
	AWS_USE_PATH_STYLE_ENDPOINT=false

	KEY_PRIVATE="-----BEGIN RSA PRIVATE KEY-----
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
	-----END RSA PRIVATE KEY-----"

	KEY_PUBLIC="-----BEGIN PUBLIC KEY-----
	MIIBITANBgkqhkiG9w0BAQEFAAOCAQ4AMIIBCQKCAQB7dzRms1i0D9Lrxc3knz/Z
	qeU4FxCXPTl30szwL/DF+VnY2D9lFOcqEU2PFQBCpSnkWQ8OLZ4n5BBnkCQGDXai
	shXu03/ndnQw7gyZ6C5ROCo5WLQRFkO4TzSj4SH3ZS2ydADDX31RMg6FIRspzQji
	s1TFm/RRoMXVyuWPNZ4CP4H1dSrNj+1UzRAtJiiq5Y8x8GyaHI78k6xUI2IjnkmO
	gWOehfXzDqlIDdpASO77G/sRRp9IDlVwfI4KLzwFG1S3vNFgmYUxe5KOSNgrgNRT
	9h2BgJTP/y5SEft8OM1n8GwEA4Cv2Jx9ypJ+myTaItIN35496EYKtP69IOUt93Mj
	AgMBAAE=
	-----END PUBLIC KEY-----"
	```
4. Execute as migrações: `php artisan migrate`
5. Inicie o servidor de desenvolvimento: `php artisan serve`

A API estará disponível em http://localhost:8000.

## Rotas da API
- `GET /api/personagem` - Lista todos os personagens.
- `POST /api/personagem` - Cria um novo personagem.
- `GET /api/personagem/{id}` - Mostra um personagem específico.
- `PUT /api/personagem/{id}` - Atualiza um personagem específico.
- `DELETE /api/personagem/{id}` - Exclui um personagem específico.
- `GET /api/buscar-cep/{cep}` - Busca informações de endereço pelo CEP.
- `POST /login/google` - Login com Google.
- `POST /logout` - Logout.

## Contribuição
Sinta-se à vontade para contribuir com este projeto. Basta fazer um fork, criar uma nova branch e enviar um pull request.

## Licença
Este projeto está licenciado sob a licença MIT.
