<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
	use SoftDeletes;
	public $with = ['users'];
    protected $fillable = [
		'name', 'email', 'phone', 'address'
	];
	/**
	 * The users that belong to the role.
	 */
	public function users() {
		return $this->hasMany('App\User','branch_id');
	}
	public function shipments() {
		return $this->hasMany('App\Shipment','branch_id');
	}
}
