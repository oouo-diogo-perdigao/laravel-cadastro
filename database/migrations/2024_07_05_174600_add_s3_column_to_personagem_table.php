<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddS3ColumnToPersonagemTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('personagem', function (Blueprint $table) {
			// Adiciona a coluna 's3' como string, permitindo nulo
			$table->string('s3')->nullable()->after('imagem');

			// Modifica a coluna 'imagem' para não aceitar valores nulos
			$table->string('imagem')->nullable(false)->change();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('personagem', function (Blueprint $table) {
			// Reverte as alterações feitas no método 'up'
			$table->dropColumn('s3');
			$table->string('imagem')->nullable()->change();
		});
	}
}
