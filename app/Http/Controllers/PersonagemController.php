<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Personagem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PersonagemController extends Controller
{
	/**
	 * GET Mostra todos os personagens
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function index()
	{
		// $personagens = Personagem::all();
		$personagens = Personagem::select('id', 'nome', 'imagem')->get();

		return response()->json($personagens);
	}

	/**
	 * POST Registra personagem no bd.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'nome' => 'required',
			'imagem' => 'required|file|max:10240', // Aceita apenas arquivos até 10240 KB

			'cpf' => 'required|digits:11',
			'cep' => 'required|regex:/^\d{5}-?\d{3}$/',
			'pais' => 'required|string|max:255',
			'estado' => 'required|string|max:2',
			'cidade' => 'required|string|max:255',
			'bairro' => 'required|string|max:255',
			'rua' => 'required|string|max:255',
			'numero' => 'required|string|max:255',
			'complemento' => 'nullable|string|max:255',
			'referencia' => 'nullable|string|max:255',
		]);

		if ($validator->fails()) {
			return response()->json(['error' => $validator->errors()], 422);
		}

		$personagem = new Personagem();
		$personagem->nome = $request->nome;
		$personagem->cpf = $request->cpf;
		$personagem->cep = $request->cep;
		$personagem->pais = $request->pais;
		$personagem->estado = $request->estado;
		$personagem->cidade = $request->cidade;
		$personagem->bairro = $request->bairro;
		$personagem->rua = $request->rua;
		$personagem->numero = $request->numero;
		$personagem->complemento = $request->complemento;
		$personagem->referencia = $request->referencia;

		if ($request->hasFile('imagem') && $request->file('imagem')->isValid()) {
			$imagem = $request->file('imagem');

			// Gere um nome único para a imagem
			$imagemNome = time() . '_' . $imagem->getClientOriginalName();

			try {
				Storage::disk('s3')->putFileAs('imagens', $imagem, $imagemNome);
				// Armazene a URL da imagem no seu modelo
				$personagem->s3 = 'imagens/' . $imagemNome;
				$personagem->imagem = Storage::disk('s3')->url($personagem->s3);

			} catch (\Exception $e) {
				// Lidar com erros de upload
				return response()->json(['error' => 'Erro ao enviar imagem'], 500);
			}
		}

		$personagem->save();

		return response()->json($personagem);
	}

	/**
	 * Mostra 1 persongem
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function show($id)
	{
		$personagem = Personagem::find($id);

		if (!$personagem) {
			return response()->json(['error' => 'Personagem não encontrado'], 404);
		}

		return response()->json([
			'id' => $personagem->id,
			'nome' => $personagem->nome,
			'imagem' => $personagem->imagem,
			'created_at' => $personagem->created_at,
			'updated_at' => $personagem->updated_at
		]);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Models\Personagem  $personagem
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		$request->validate([
			'nome' => 'required',
		]);

		$personagem = Personagem::findOrFail($id); // Encontra o personagem pelo ID
		$personagem->nome = $request->nome;
		$personagem->save();
		return response()->json($personagem);
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function destroy($id)
	{
		try {
			$personagem = Personagem::find($id);

			if (!$personagem) {
				return response()->json(['error' => 'Personagem não encontrado'], 404);
			}

			// Excluir a imagem associada, se existir
			if ($personagem->s3) {
				// Exemplo de exclusão no Amazon S3:
				Storage::disk('s3')->delete($personagem->s3);
			}

			// Excluir o personagem do banco de dados
			$personagem->delete();

			return response()->json(['message' => 'Personagem deletado com sucesso']);
		} catch (\Exception $e) {
			return response()->json(['error' => 'Erro ao excluir o personagem', 'details' => $e->getMessage()], 500);
		}
	}
}
