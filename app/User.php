<?php

namespace App;

use App\Notifications\verifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Laravel\Passport\HasApiTokens;
// use Spatie\Permission\Traits\HasRoles;
// use Spatie\Permission\Models\Role;
// use Spatie\Permission\Models\Permission;
use Auth;

class User extends Authenticatable {
	use Notifiable, SoftDeletes;
	// use HasRoles;
	public $with = ['roles'];
	protected $guard_name = 'web';
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'email', 'password', 'verifyToken', 'status', 'company',
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
	public function roles() {
		return $this->belongsToMany('App\Role');
	}
	
	public function branch() {
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
	
	
	public function documents() {
		return $this->hasMany('App\Attachment', 'client_id');	
	}

	public function row()
	{
		return $this->hasOne('App\Rows', 'user_id');
	}

	/**
     * Get all user permissions.
     *
     * @return bool
     */
    // public function getAllPermissionsAttribute()
    // {
    //     return $this->getAllPermissions();
    // }
    
     /**
     * Get all user permissions in a flat array.
     *
     * @return array
     */
    // public function getCanAttribute()
    // {
    //     $permissions = [];
    //     foreach (Permission::all() as $permission) {
    //         if (Auth::user()->can($permission->name)) {
    //             $permissions[$permission->name] = true;
    //         } else {
    //             $permissions[$permission->name] = false;
    //         }
    //     }
    //     return $permissions;
	// }
	/**
	* The accessors to append to the model's array form.
	*
	* @var array
	*/
	// protected $appends = ['all_permissions','can'];

}
