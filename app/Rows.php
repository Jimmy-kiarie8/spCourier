<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rows extends Model
{
    protected $fillable = [
		'rows', 'user_id'
	];
    public function user()
    {
        return $this->hasOne('App\User');
    }
}
