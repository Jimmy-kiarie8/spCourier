<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShipmentRequest;
use App\Shipment;
use App\User;
use Illuminate\Support\Carbon;
use App\ShipmentStatus;
use App\Product;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Notifications\ShipmentNoty;
use App\Notifications\NotyExcel;
use Notification;

class ShipmentController extends Controller {
	public function getShipments() {
		// $roles = Auth::user()->roles;
		// $user = User::find(1);
		// $roles = $user->hasrolesTo('Edit Users');
		// // var_dump($roles);die;
		// if ($roles) {
		// 	return 'wpooo';
		// 	foreach ($roles as $role) {
		// 		$role_name = $role->name;
		// 	}
		// // return $role_name;
		// if ($role_name == 'Admin') {
		// 	return Shipment::orderby('id', 'asc')->get();
		// }
		// $results = Shipment::orderby('id', 'asc')->get();
		// // $results = Shipment::where('branch_id', Auth::user()->branch_id)->get();
		// return json_decode(json_encode($results), true);
		// }else{
		// 	return 'qqqsss';
		// }
		// $shipment = [];
		// $res = Shipment::chunk(100, function($shipments) use (&$shipment) {
		// 	foreach ($shipments as $val) {
		// 		 $shipment[] = $val;
		// 	}
		// });
			// return Shipment::latest()->limit(500)->get();
		return Shipment::latest()->take(500)->get();

			// return $shipment;
		// return $shipment;
		// $results = [];
		// $req = Shipment::chunk(20, function ($shipment) {
		// 	foreach ($shipment as $flight) {
		// 		$results[] = $flight;
		// 	}
		// });
		// return $results;
	}

	public function csv() {
		return view('csv.csv');
	}

	/**
	 * Search the products table.
	 *
	 * @param  Request $request
	 * @return mixed
	 */
	public function search(Request $request) {
		// First we define the error message we are going to show if no keywords
		// existed or if no results found.
		$error = ['error' => 'No results found, please try with different keywords.'];

		// Making sure the user entered a keyword.
		if ($request->has('q')) {

			// Using the Laravel Scout syntax to search the products table.
			$posts = Shipment::search($request->get('q'))->get();

			// If there are results return them, if none, return the error message.
			return $posts->count() ? $posts : $error;

		}

		// Return the error message if no keywords existed
		return $error;
	}

	/**
	 * import a file in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function import(Request $request) {
		// var_dump($request->all());die;
		$user = User::find($request->client);
		// return $user->city;
		// $message = 'New shipments have been added';
		// Notification::send(Auth::user(), new NotyExcel($message));
		// return redirect('courier#/Shipments');
		// var_dump($request->client);die;
		if ($request->file('shipment')) {
			$path = $request->file('shipment')->getRealPath();
			$data = Excel::load($path, function ($reader) {

			})->get();

			if (!empty($data) && $data->count()) {
				foreach ($data->toArray() as $row) {
					if (!empty($row)) {
						$dataArray[] =
							[
							'client_name' => $row['name_of_the_client'],
							'order_id' => $row['order_id'],
							'client_email' => $row['sender_mail'],
							'client_phone' => $row['phone'],
							'client_address' => $row['address'],
							'client_city' => $row['city'],
							'amount_ordered' => $row['quantity'],
							// 'client_postal_code' => $row['postal_code'],
							'cod_amount' => $row['cod_amount'],
							'client_region' => $row['region'],
							// 'booking_date' => $row['order_date']->formatDates(true),
							// 'product_no' => $row['How_many_pieces_of_the_product_are_contained_in_the_order'],
							'airway_bill_no' => $row['order_id'],
							'bar_code' => $row['order_id'],
							// 'derivery_date' => $row['derivery_date'],
							// 'order_date' => $row['order_date'],
							'user_id' => Auth::id(),
							'status' => 'Warehouse',
							'created_at' => new DateTime(),
							'booking_date' => new DateTime(),
							'updated_at' => new DateTime(),
							'shipment_id' => random_int(1000000, 9999999),
							// 'sender_name' => Auth::user()->name,
							// 'sender_email' => Auth::user()->email,
							// 'sender_phone' => Auth::user()->phone,
							// 'sender_address' => Auth::user()->address,
							// 'sender_city' => Auth::user()->city,
							// 'user_id' => Auth::id(),
							'paid' => 0,
							'printed' => 0,
							'sender_name' => $user->name,
							'sender_email' => $user->email,
							'sender_phone' => $user->phone,
							'sender_address' => $user->address,
							'sender_city' => $user->city,
							'client_id' => $request->client,
						];
					}
				}
				if (!empty($dataArray)) {
					Shipment::insert($dataArray);
				}
				// Notification::send(Auth::user(), new ShipmentNoty($shipment));
				return redirect('courier#/Shipments');
				
			}
		}else{
			var_dump('something went wrong');
		}
	}

	public function export() {
		$model = Shipment::where('branch_id', Auth::user()->branch_id)->get();
		$results = Excel::create('Shipment', function ($excel) {

			$excel->sheet('Sheetname', function ($sheet) {

				$sheet->fromModel(Shipment::all());

			});

		})->export('csv');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		// return $request->all();
		$this->Validate($request, [
			// 'form.client_name' =>'required',
			// 'form.client_phone' =>'required|numeric',
			// 'form.client_email' =>'required|email',
			// 'form.client_address' =>'required',
			// 'form.client_city' =>'required',
			// 'form.assign_staff' =>'required',
			// 'form.shipment_type' =>'required',
			'form.payment' =>'required',
			'form.insuarance_status' =>'required',
			'form.booking_date' =>'required|date',
			'form.derivery_date' =>'required|date',
			'form.bar_code' =>'required',
			'form.derivery_time' =>'required',
			'form.from_city' =>'required',
			'form.to_city' =>'required',
			// 'form.sender_name' =>'required',
			// 'form.sender_phone' =>'required',
			// 'form.sender_address' =>'required',
			// 'form.sender_city' =>'required',
			// 'form.airway_bill_no' =>'required',
			// 'form.total_freight' =>'required|numeric',
		]);
		

		$products = collect($request->form['products'])->transform(function ($product) {
			$product['total'] = $product['quantity'] * $product['price'];
			$product['user_id'] = Auth::id();
			return new Product($product);
		});

		// return $products;

		if ($products->isEmpty()) {
			return response()->json([
				'product_empty' => ['One or more products is required'],
			], 422);
		}
		$shipment = new Shipment;
		$shipment->sub_total = $products->sum('total');
		$shipment->client_name = $request->form['client_name'];
		$shipment->client_phone = $request->form['client_phone'];
		$shipment->client_email = $request->form['client_email'];
		$shipment->client_address = $request->form['client_address'];
		$shipment->client_city = $request->form['client_city'];
		// $shipment->assign_staff = $request->form['assign_staff'];
		$shipment->airway_bill_no = $request->form['bar_code'];
		$shipment->shipment_type = $request->form['shipment_type'];
		$shipment->payment = $request->form['payment'];
		// $shipment->total_freight = $request->form['total_freight'];
		// $shipment->total = $request->form['total'];
		$shipment->insuarance_status = $request->form['insuarance_status'];
		$shipment->status = $request->form['status'];
		$shipment->booking_date = $request->form['booking_date'];
		$shipment->derivery_date = $request->form['derivery_date'];
		$shipment->derivery_time = $request->form['derivery_time'];
		$shipment->bar_code = $request->form['bar_code'];
		$shipment->to_city = $request->form['to_city'];
		$shipment->cod_amount = $request->form['cod_amount'];
		// $shipment->receiver_name = $request->form['receiver_name'];
		$shipment->from_city = $request->form['from_city'];
		$shipment->branch_id = $request->selectB['id'];
		// $shipment->driver = $request->selectD['id'];
		$shipment->client_id = $request->model;
		$sender = User::find($request->model);
		// return $request->form['customer_id'];
		$shipment->sender_name = $sender->name;
		$shipment->sender_email = $sender->email;
		$shipment->sender_phone = $sender->phone;
		$shipment->sender_address = $sender->address;
		$shipment->sender_city = $sender->city;
		$shipment->user_id = Auth::id();
		$shipment->shipment_id = random_int(1000000, 9999999);
		// $shipment->branch_id = Auth::user()->branch_id;
		$users = $this->getAdmin();
		if ($shipment->save()) {
			$shipment->products()->saveMany($products);
		}
		Notification::send($users, new ShipmentNoty($shipment));
		// $users->notify(new ShipmentNoty($shipment));
		return $shipment;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Shipment  $shipment
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Shipment $shipment) {
		// return $request->all();
		$shipment = Shipment::find($request->id);
		$shipment->client_name = $request->client_name;
		$shipment->client_phone = $request->client_phone;
		$shipment->client_email = $request->client_email;
		$shipment->client_address = $request->client_address;
		$shipment->client_city = $request->client_city;
		// $shipment->assign_staff = $request->assign_staff;
		$shipment->airway_bill_no = $request->airway_bill_no;
		$shipment->shipment_type = $request->shipment_type;
		// $shipment->customer_id = $request->customer_id;
		$shipment->payment = $request->payment;
		
		$shipment->total_freight = $request->total_freight;
		$shipment->insuarance_status = $request->insuarance_status;
		$shipment->booking_date = $request->booking_date;
		$shipment->derivery_date = $request->derivery_date;
		$shipment->derivery_time = $request->derivery_time;
		$shipment->save();
		return $shipment;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Shipment  $shipment
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Shipment $shipment) {
		Shipment::find($shipment->id)->delete();
	}


	public function getAdmin()
	{
		$usersRolem = User::with('roles')->get();
		$userArr = [];
		foreach ($usersRolem as $user) {
				// var_dump($user->roles); die;
			foreach ($user->roles as $role) {
				if ($role->name == 'Admin') {
					$userArr[] = $role->pivot->user_id;		
				}
			}
		}
		$users = $userArr;
		return $admin = User::whereIn('id', $userArr)->get();
	}
	
	public function updateStatus(Request $request, Shipment $shipment, $id) {
		// return $request->all();
		$shipment = Shipment::find($request->id);
		// if ($request->address) {
		// 	$coordinates = serialize($request->address);
		// 	$latitude = $request->address['latitude'];
		// 	$longitude = $request->address['longitude'];
		// 	$coords = array('lat' => $latitude, 'lng' => $longitude);
		// 	$shipment->coordinates = $coordinates;
		// 	$shipment->longitude = $longitude;
		// 	$shipment->latitude = $latitude;
		// }
		// $shipment->location = $request->formobg['location'];
		$shipment->status = $request->formobg['status'];
		// var_dump($request->formobg['status']); die;
		$shipment->derivery_date = $request->formobg['derivery_date'];
		$shipment->derivery_time = $request->formobg['derivery_time'];
		$shipment->remark = $request->formobg['remark'];
		$shipment->speciial_instruction = $request->formobg['remark'];
		// $shipment->save();
		if ($shipment->save()) {
			$shipStatus = Shipment::find($id);
			$statusUpdate = new ShipmentStatus;
			$statusUpdate->remark = $request->formobg['remark'];
			$statusUpdate->status = $request->formobg['status'];
			$statusUpdate->location = $request->formobg['location'];
			// $statusUpdate->derivery_time = $request->formobg['derivery_time'];
			$statusUpdate->user_id = Auth::id();
			$statusUpdate->branch_id = Auth::user()->branch_id;
			$statusUpdate->shipment_id = $id;
			// return $statusUpdate;
			$statusUpdate->save();
		}
		return $shipment;
	}

	public function UpdateShipment(Request $request, Shipment $shipment)
	{
		// return $request->all();
		$id = [];
		foreach ($request->selected as $selectedItems ) {
			$id[] = $selectedItems['id'];
		}
		$status = $request->form['status'];
		$derivery_time = $request->form['derivery_time'];
		$remark = $request->form['remark'];
		// $location = $request->form['location'];
		$derivery_date = $request->form['scheduled_date'];
		$shipment = Shipment::whereIn('id', $id)->update(['status' => $status, 'remark' => $remark, 'derivery_date' => $derivery_date, 'derivery_time' => $derivery_time, 'speciial_instruction' => $remark]);
		$shipStatus = Shipment::whereIn('id', $id)->get();
		foreach ($shipStatus as $statuses) {
			$statusUpdate = new ShipmentStatus;
			$statusUpdate->remark = $request->form['remark'];
			$statusUpdate->status = $request->form['status'];
			$statusUpdate->location = $request->form['location'];
			// $statusUpdate->derivery_time = $request->form['derivery_time'];
			$statusUpdate->user_id = Auth::id();
			$statusUpdate->branch_id = Auth::user()->branch_id;
			$statusUpdate->shipment_id = $statuses->id;
			// return $statusUpdate;
			$statusUpdate->save();
			// return $statusUpdate;
		}
		// $shipStatus->statuses()->saveMany($shipStatus);

		return $shipment;
	}

	public function assignDriver(Request $request, Shipment $shipment)
	{
		$id = [];
		foreach ($request->selected as $selectedItems ) {
			$id[] = $selectedItems['id'];
		}
		$driver = $request->form['driver'];
		$remark = $request->form['remark'];
		$shipment = Shipment::whereIn('id', $id)->update(['driver' => $driver, 'remark' => $remark]);
		return $shipment;
	}

	public function assignBranch(Request $request, Shipment $shipment)
	{
		// return $request->all();
		$id = [];
		foreach ($request->selected as $selectedItems ) {
			$id[] = $selectedItems['id'];
		}
		// $status = $request->form['status'];
		// $remark = $request->form['remark'];
		// $shipment = Shipment::whereIn('id', $id)->update(['branch_id' => $branch, 'remark' => $remark]);
		// return $shipment;
		$branch = $request->form['branch_id'];
		$remark = $request->form['remark'];
		$shipment = Shipment::whereIn('id', $id)->update(['branch_id' => $branch, 'remark' => $remark]);
		// return $shipStatus = Shipment::whereIn('id', $id)->get();
		
	}


	// Dashboard
	public function delayedShipment() {
		return Shipment::where('status', 'delayed')->where('branch_id', Auth::user()->branch_id)->get();
	}

	public function approvedShipment() {
		return Shipment::where('status', 'approved')->where('branch_id', Auth::user()->branch_id)->get();
	}

	public function waitingShipment() {
		return Shipment::where('status', 'waiting approval')->where('branch_id', Auth::user()->branch_id)->get();
	}

	public function deriveredShipment() {
		return Shipment::where('status', 'derivered')->where('branch_id', Auth::user()->branch_id)->get();
	}

	public function scheduled() {
		// return Shipment::where('status', 'Scheduled')->get();
		$all_shipment = Shipment::latest()->take(300)->get();
		foreach ($all_shipment as $shipment) {
			$derivery_date = new Carbon($shipment->derivery_date);
			$date1 = Carbon::today();
			$date2 = new Carbon('tomorrow');
        	$date2->diffInDays($date1);
        	$shipment = Shipment::whereBetween('created_at', [$date1, $date2])->where('status', 'Scheduled')->latest()->take(300)->get();
		}
		return $shipment;
	}

	// Chart
	public function getChartData() {
		$shipments = DB::table('products')
			->select(DB::raw('count(id) as count, date_format(created_at, "%M %d") as date'))
			->orderBy('created_at', 'desc')
			->groupBy('date')
			->where('branch_id', Auth::user()->branch_id)
			->get();

		$lables = [];
		$rows = [];
		foreach ($shipments as $shipment) {
			$lables[] = $shipment->date;
			$rows[] = $shipment->count;
		}
		$data = [
			'lables' => $lables,
			'rows' => $rows,
		];
		return $data;
	}

	public function filterShipment(Request $request)
	{
		// return $request->all();
		if ($request->form['start_date'] == '' || $request->form['end_date'] == '') {
			if ($request->select['id'] == 'all') {
				if ($request->selectStatus['state'] == 'All') {
				// return 'st6';
				return Shipment::latest()->take(500)->get();	
				}else{
				// return 'st5';
				return Shipment::where('status', $request->selectStatus['state'])->latest()->take(500)->get();
				}
				
			}else{
				// return 'st4';
				return Shipment::where('branch_id', $request->select['id'])->latest()->take(500)->get();
			}
		}else{
			if ($request->select['id'] == 'all') {
				if ($request->selectStatus['state'] == 'All') {
				// return 'st1';
				return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->latest()->take(500)->get();
				}else{
				// return 'st2';
				return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->latest()->take(500)->where('status', $request->selectStatus['state'])->get();
				}
			}else{
				if ($request->selectStatus['state'] == 'All') {
				// return 'st1';
					return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('branch_id', $request->select['id'])->where('status', $request->selectStatus['state'])->latest()->take(500)->get();
				}else{
				// return 'st2';
					return Shipment::whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('branch_id', $request->select['id'])->latest()->take(500)->where('status', $request->selectStatus['state'])->get();
				}
				// return 'st3';
				// return Shipment::where('branch_id', $request->select['id'])
				// 				// ->where('status', $request->selectStatus['state'])
				// 				->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])
				// 				->latest()->take(500)->get();
			}
		}

	}
	public function betweenShipments(Request $request)
	{
		return Shipment::latest()->skip($request->end)->take(500)->get();
	}

	public function getScheduled(Request $request)
	{
		// return $request->all();
		return Shipment::where('status', 'Scheduled')->whereBetween('derivery_date', [$request->start_date, $request->end_date])->where('printed', 0)->get();
	}
}
