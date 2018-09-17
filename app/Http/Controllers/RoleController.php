<?php

namespace App\Http\Controllers;
use Illuminate\Support\Carbon;
use App\Notifications\scheduleNotification;

use App\Role_user;
use App\Rolem;
use App\User;
use App\Shipment;
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
		// $role = new Role;
		// $role->name = $request->name;
		// $role->description = $request->description;
		// $role->save();
		// return $request->all();
		
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
	public function update(Request $request, Role_user $role_user, $id) {
		$role = Role::find($id);
		$role->name = $request->name;
		$role->description = $request->description;
		$role->save();
		return $role;
	}

	public function destroy(Rolem $role) {
		// return $role->id;
		Role::find($role->id)->delete();
	}
	
	public function getRoles()
	{
		return	Role::all();
	}

	public function carbon(Request $request)
	{
		$user = User::find(6);
		return $user->assignRole('Admin');
		return	$user->givePermissionTo('add user');
		$all_shipment = Shipment::setEagerLoads([])->get();
		$today = Carbon::now();
		 $user = User::find(1);
		// foreach ($all_shipment as $shipment) {
		// 	$derivery_date = new Carbon($shipment->derivery_date);
		// 	$date1 = Carbon::today();
		// 	$date2 = new Carbon('tomorrow');
        // 	$date2->diffInDays($date1);
        // 	$shipment = Shipment::whereBetween('created_at', [$date1, $date2])->setEagerLoads([])->get();
		// }



		
		$fromDate = Carbon::today();
		$prev_month = $today->subMonth();

		$toDate = '2018-08-17';
		$sortBy = 'id';
	
		// Report title
		$title = 'Registered User Report';
	
		// For displaying filters description on header
		$meta = [
			'Report From' => $prev_month . ' To ' . $fromDate,
			'Sort By' => $sortBy
		];
		

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
		$cust_emails = $customers->map(function ($customer) {
			return $customer->only('email', 'name', 'id');
		});

		foreach ($cust_emails as $mails) {
		$email = $mails['email'];
			
		// Do some querying..
		return $queryBuilder = Shipment::whereBetween('created_at', [$prev_month, $fromDate])
							->where('client_id', $mails['id'])
							->orderBy($sortBy)->get();
		if ($queryBuilder->count() > 0) {
			$columns = [
				'airway bill no',
				'sender name',
				'sender city',
				'sender phone',
				'client email',
				'client city',
				'client phone',
				'amount ordered',
				'derivery date',
			];
			
			$pdf = PdfReport::of($title, $meta, $queryBuilder, $columns)
							->editColumn('created at', [
								'displayAs' => function($result) {
									return $result->created_at->format('d M Y');
								}
							])
							->setCss([
								'.head-content' => 'border-width: 1px',
							])
							->limit(10)
							->stream(); // or download('filename here..') to download pdf
			Mail::send(new ReportMail($user, $email, $pdf));
		}	
	}

}
}
