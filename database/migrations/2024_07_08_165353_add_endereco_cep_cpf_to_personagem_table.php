<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	public function up()
	{
		Schema::table('personagem', function (Blueprint $table) {
			$table->string('cep')->nullable();
			$table->string('pais')->nullable();
			$table->string('estado')->nullable();
			$table->string('cidade')->nullable();
			$table->string('bairro')->nullable();
			$table->string('rua')->nullable();
			$table->string('numero')->nullable();
			$table->string('complemento')->nullable();
			$table->string('referencia')->nullable();
			$table->string('cpf')->nullable();
		});
	}

	public function down()
	{
		Schema::table('personagem', function (Blueprint $table) {
			$table->dropColumn(['cep', 'pais', 'estado', 'cidade', 'bairro', 'rua', 'numero', 'complemento', 'referencia', 'cpf']);
		});
	}
};
