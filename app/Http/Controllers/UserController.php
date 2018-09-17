<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsersRequest;
use App\Notifications\SignupActivate;
use App\Role_user;
use App\User;
use App\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {
	public function getUsers() {
		return User::with(['roles'])->get();
		// return User::with(['roles'])->where('branch_id', Auth::user()->branch_id)->get();
	}
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(UsersRequest $request) {
		// return $request->all();
		// var_dump($request->form); die;
		$user = new User;
		$password = $request->password;
		$password_hash = Hash::make($request->password);
		$user->name = $request->name;
		$user->password = $password_hash;
		$user->name = $request->form['name'];
		$user->email = $request->form['email'];
		$user->phone = $request->form['phone'];
		$user->branch_id = $request->form['branch_id'];
		$user->address = $request->form['address'];
		$user->city = $request->form['city'];
		$user->country = $request->form['country'];
		$user->activation_token = str_random(60);
		$user->save();
		$user->assignRole($request->role_id);
		$user->givePermissionTo($request->selected);
		// $user->syncRoles($request->form['role_id']);
		
		// if ($user->save()) {
		// 	$user_role = new Role_user;
		// 	$user_role->user_id = $user->id;
		// 	$user_role->role_id = $request->role_id;
		// 	$user_role->save();

		// 	// $user_branch = new Branch_user;
		// 	// $user_branch->user_id = $user->id;
		// 	// $user_branch->branch_id = $request->branch_id;
		// 	// $user_branch->save();
		// }
        $user->notify(new SignupActivate($user, $password));
		return $user;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\User  $user
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, User $user) {
		// return $request->all();
		$user = User::find($request->form['id']);
		$user->name = $request->form['name'];
		$user->email = $request->form['email'];
		$user->phone = $request->form['phone'];
		$user->branch_id = $request->form['branch_id'];
		$user->address = $request->form['address'];
		$user->city = $request->form['city'];
		$user->country = $request->form['country'];
		$user->save();
		$user->givePermissionTo($request->selected);
		$user->syncRoles($request->form['role_id']);
		// $user->assignRole($request->role_id);
		// if ($user->save()) {
		// 	if (!$request->role_id) {
		// 		return $user;
		// 	}else{
		// 		$user_role = Role_user::where('user_id', $request->id)->get();
		// 		$user_id = $user->id;
		// 		$role_id = $request->role_id;
		// 		$user_role = Role_user::updateOrCreate(
		// 			['user_id' => $user_id],
		// 			['user_id' => $user_id, 'role_id' => $role_id]
		// 		);
		// 	}
		// }
		// $user->save();
		return $user;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\User  $user
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(User $user) {
		User::find($user->id)->delete();
	}

	public function getLogedinUsers() {
		return Auth::user();
	}

	public function profile(Request $request, User $user, $id) {
		// return $request->all;
		$upload = User::find($request->id);
		if ($request->hasFile('image')) {
			$imagename = time() . $request->image->getClientOriginalName();
			$request->image->storeAs('public/profile', $imagename);
			// return response();
		}
		$image_name = '/storage/profile/' . $imagename;
		$upload->profile = $image_name;
		$upload->save();
	}

	public function getDrivers()
	{
		$users = User::with('roles')->get();
		$userArr = [];
		foreach ($users as $user) {
				// var_dump($user->roles); die;
			foreach ($user->roles as $role) {
				if ($role->name == 'Driver') {
					$userArr[] = $role->pivot->user_id;		
				}
			}
		}
		$drivers = User::whereIn('id', $userArr)->get();
		return $drivers;
	}

	public function getCustomer()
	{
		$users = User::with('roles')->get();
		$userArr = [];
		foreach ($users as $user) {
			foreach ($user->roles as $role) {
				if ($role->name == 'Customer') {
					$userArr[] = $role->pivot->user_id;		
				}
			}
		}
		$customers = User::whereIn('id', $userArr)->get();
		return json_decode(json_encode($customers));
	}

	public function getSorted(Request $request)
	{
		// return $request->all();
		$roles = User::all();
		$users_id = [];
		if ($request->abbr == 'all') {
			return $roles;
		}else{
			foreach ($roles as $role) {
				foreach ($role->roles as $user_role) {
					if ($user_role->pivot->role_id == $request->abbr) {
						$users_id[] = $user_role->pivot->user_id;
					}
				}
			}
		}
		return User::whereIn('id', $users_id)->get();
		// return $users_id;
		// return User::where()->get();
	}

	public function getUserPro(Request $request, $id)
	{
		return Shipment::where('client_id', $id)->get();
	}
}
