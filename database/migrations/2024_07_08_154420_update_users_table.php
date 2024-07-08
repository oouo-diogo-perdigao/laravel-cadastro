<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::table('users', function (Blueprint $table) {

			$table->string('nick')->nullable()->after('name');
			$table->timestamp('last_login')->nullable()->after('created_at');
			$table->string('language')->default('pt-br')->after('last_login');
			$table->string('avatar')->nullable()->after('language');
			$table->dropColumn(['password', 'email_verified_at']);
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::table(
			'users',
			function (Blueprint $table) {
				$table->dropColumn(['nick', 'last_login', 'language', 'avatar']);
				$table->string('password')->after('email');
				$table->timestamp('email_verified_at')->nullable()->after('email');
			}
		);
	}

};
