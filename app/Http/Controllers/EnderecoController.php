<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class EnderecoController extends Controller
{
	public function buscarCep(Request $request, $cep)
	{

		if (!$cep) {
			return response()->json(['error' => 'CEP não fornecido'], 400);
		}

		// Validação do CEP
		$validator = Validator::make(['cep' => $cep], [
			'cep' => 'required|cep_formato',
		]);

		// Verifica se a validação falhou
		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()->first()], 400);
		}

		// Formata o CEP para o formato desejado (remover traço, se houver)
		$cep = str_replace('-', '', $cep);

		// Realize a requisição para um serviço de busca de CEP (exemplo usando ViaCEP)
		$response = Http::get("https://viacep.com.br/ws/{$cep}/json/");

		if ($response->failed()) {
			return response()->json(['error' => 'Erro ao buscar o CEP'], $response->status());
		}

		// Extrair os dados relevantes da resposta
		$cepData = [
			'cep' => $response['cep'],
			'pais' => "Brasil",
			"estado" => $response['uf'],
			'cidade' => $response['localidade'],
			'bairro' => $response['bairro'],
			'rua' => $response['logradouro'],
		];

		return response()->json($cepData, 200);
	}
}
