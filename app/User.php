<?php

namespace App;

use App\Notifications\verifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Auth;

class User extends Authenticatable
{
	use Notifiable, SoftDeletes;
	use HasRoles, HasApiTokens;
	public $with = ['roles'];
	protected $guard_name = 'web';
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'email', 'password', 'verifyToken', 'status',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];


	/**
	 * The roles that belong to the user.
	 */
	public function country()
	{
		return $this->belongsToMany('App\Country');
	}

	public function branch()
	{
		return $this->belongsTo('App\Branch');
	}

	public function verified()
	{
		return $this->verifyToken === null;
	}

	public function sendVerificationEmail()
	{
		$this->notify(new verifyEmail($this));
	}


	public function documents()
	{
		return $this->hasMany('App\Attachment', 'client_id');
	}

	public function rows()
	{
		return $this->hasMany('App\Rows');
	}

	/**
	 * Get all user permissions.
	 *
	 * @return bool
	 */
	public function getAllPermissionsAttribute()
	{
		return $this->getAllPermissions();
	}
}
