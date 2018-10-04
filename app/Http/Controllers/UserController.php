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
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;

class UserController extends Controller {
	public function getUsers() {
		return User::with(['roles'])->get();
	}
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		$this->Validate($request, [
			'form.name' => 'required',
            'form.password' => 'required|min:6',
            'form.email' => 'required|email',
            'form.phone' => 'required|numeric',
            'form.branch_id' => 'required',
            'form.address' => 'required',
            'form.city' => 'required',
            'form.country' => 'required',
            'form.role_id' => 'required'
		]);
		// return $request->all();
		$user = new User;
		$password = Str::random(10);
		// return $request->form['name'];
		$password_hash = Hash::make($password);
		// $user->name = $request->name;
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
		// $user->givePermissionTo($request->selected);
		$user->syncRoles($request->form['role_id']);
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
		$users = User::all();
		$userArr = [];
		foreach ($users as $user) {
			if ($user->hasRole('Rider')) {
				$userArr[] = $user;
			}
		}
		return $userArr;
	}

	public function getCustomer()
	{
		$users = User::all();
		$userArr = [];
		foreach ($users as $user) {
			if ($user->hasRole('Client')) {
				$userArr[] = $user;
			}
		}
		return $userArr;
	}

	public function getSorted(Request $request)
	{
		// return $request->all();
		$roles = User::all();
		$users_id = [];
			$users = User::all();
			$userArr = [];
			foreach ($users as $user) {
				if ($user->hasRole($request->name)) {
					$userArr[] = $user;
				}
			}
			return $userArr;
	}

	public function getUserPro(Request $request, $id)
	{
		return Shipment::where('client_id', $id)->get();
	}

	public function getUserPerm(Request $request, $id)
	{
		$user = User::find($id);
		$permissions = $user->getAllPermissions();
        $can = [];
        foreach ($permissions as $perm) {
            $can[] = $perm->name;
        }
        return $can;
	}

	public function password(Request $request)
	{
		$this->Validate($request, [
			'password' => 'required|string|min:6',
			// 'password' => 'required|string|min:6|confirmed',
		]);
		$user = User::find(Auth::id());
		$user->password =  Hash::make($request->password);
		$user->save();
		return $user;
	}
}
