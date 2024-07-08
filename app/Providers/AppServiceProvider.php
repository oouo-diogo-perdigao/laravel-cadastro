<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 */
	public function boot()
	{
		Validator::extend('cep_formato', function ($attribute, $value, $parameters, $validator) {
			// Verifica se o formato do CEP é válido (XXXXX-XXX)
			return preg_match('/^\d{5}-?\d{3}$/', $value);
		});

		Validator::replacer('cep_formato', function ($message, $attribute, $rule, $parameters) {
			return str_replace(':attribute', $attribute, 'O campo :attribute deve ter um formato válido de CEP.');
		});
	}

	/**
	 * Bootstrap any application services.
	 */
	public function register(): void
	{
		//
	}

}
