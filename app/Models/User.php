<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
	protected $fillable = [
		'name',
		'nick',
		'email',
		'remember_token',
		'created_at',
		'last_login',
		'language',
		'avatar',
	];

	protected $casts = [
		'created_at' => 'datetime',
		'last_login' => 'datetime',
	];
}
