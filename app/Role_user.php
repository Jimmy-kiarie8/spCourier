<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role_user extends Model {
	use SoftDeletes;
	protected $table = 'role_user';
	protected $fillable = [
		'user_id', 'role_id',
	];
}
