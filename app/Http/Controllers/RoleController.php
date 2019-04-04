<?php

namespace App\Http\Controllers;
use Illuminate\Support\Carbon;
use App\Notifications\scheduleNotification;

use App\Role_user;
use App\Rolem;
use App\User;

use Illuminate\Http\Request;
use App\Mail\scheduleMail;
use Illuminate\Support\Facades\Mail;
use PdfReport;
use ExcelReport;
use App\Mail\ReportMail;
use Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller {
	
	public function getUsersRole() {
		$user_arr = json_decode(json_encode(User::where('branch_id', Auth::user()->branch_id)->get()), true);
		return $user_arr;

	}

	public function getPermissions()
	{
		return Permission::all();
	}

	public function store(Request $request)
	{
		// return $request->all();
		// $this->Validate($request, [
		// 	'form.name' => 'required',
		// ]);
        $role = Role::create(['name' => $request->form['name']]);
        $role->givePermissionTo($request->selected);

        // $role = Role::create(['name' => 'super-admin']);
		// $role->givePermissionTo(Permission::all());
		
		return $role;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Rolem  $role
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		// return $request->all();
		$role = Role::find($id);
		$role->name = $request->form['name'];
		$role->save();
		$role->syncPermissions($request->selected);
		return $role;
	}

	public function destroy(Rolem $role) {
		// return $role->id;
		Role::find($role->id)->delete();
	}
	
	public function getRoles()
	{
		return Role::all();
	}
	
	public function getRolesPerm(Request $request)
	{
		// return $request->all();
		return Role::findByName($request->name)->permissions->pluck('name');
	}

}
