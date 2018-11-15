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

class ShipmentController extends Controller
{
	public function getShipments()
	{
		if (Auth::user()->hasRole('Client')) {
			return Shipment::latest()->where('client_id', Auth::id())->take(500)->get();
		} else {
			return Shipment::latest()->take(500)->where('country_id', Auth::user()->country_id)->get();
		}
	}

	public function updateCancelled()
	{
		$today = Carbon::today();
		$prev_month = $today->subMonth();
		$shipments = Shipment::where('status', '!=', 'Scheduled')
			->Where('status', '!=', 'Delivered')
			->Where('status', '!=', 'Cancelled')->take(10)->get();
        // dd($prev_month);
		$ships = [];
		foreach ($shipments as $shipment) {
            // dd($shipment->created_at.'::'.$shipment->created_at->addMonth(1));
			$ships[] = Shipment::setEagerLoads([])->select('id')->where('status', '!=', 'Scheduled')
				->Where('status', '!=', 'Delivered')
                // ->where('id', $shipment->id)
				->Where('status', '!=', 'Cancelled')
				->whereDate('created_at', '<=', $prev_month)
				->take(10)->get();
		}
		$id = [];
		$arr_R = array_flatten($ships);
		foreach ($arr_R as $ship) {
			$id[] = $ship->id;
		}
		// return $shipment = Shipment::whereIn('id', $id)->take(10)->get();
		return Shipment::whereIn('id', $id)->update(['status' => 'Cancelled']);
	}

	/**
	 * Search the products table.
	 *
	 * @param  Request $request
	 * @return mixed
	 */
	public function search(Request $request)
	{
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
	public function import(Request $request)
	{
		// dd($request->all());
		$user = User::find($request->client);
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
							'cod_amount' => $row['cod_amount'],
							'client_region' => $row['region'],
							'airway_bill_no' => $row['order_id'],
							'bar_code' => $row['order_id'],
							'user_id' => Auth::id(),
							'status' => 'Warehouse',
							'created_at' => new DateTime(),
							'booking_date' => new DateTime(),
							'updated_at' => new DateTime(),
							'shipment_id' => random_int(1000000, 9999999),
							'paid' => 0,
							// 'printReceipt' => 0,
							'printed' => 0,
							'sender_name' => $user->name,
							'sender_email' => $user->email,
							'sender_phone' => $user->phone,
							'sender_address' => $user->address,
							'sender_city' => $user->city,
							'client_id' => $request->client,
							// 'country' => $request->country,
							'country_id' => $request->country_id,
						];
					}
				}
				if (!empty($dataArray)) {
					Shipment::insert($dataArray);
				}
				// Notification::send(Auth::user(), new ShipmentNoty($shipment));
				return redirect('courier#/Shipments');

			}
		} else {
			var_dump('something went wrong');
		}
	}

	public function export()
	{
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
	public function store(Request $request)
	{
		// return $request->all();
		// $this->Validate($request, [
		// 	'form.payment' =>'required',
		// 	'form.insuarance_status' =>'required',
		// 	'form.booking_date' =>'required|date',
		// 	'form.derivery_date' =>'required|date',
		// 	'form.bar_code' =>'required',
		// 	'form.derivery_time' =>'required',
		// 	'form.from_city' =>'required',
		// 	'form.to_city' =>'required',
		// ]);



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
		if ($request->selectCl == []) {
			$shipment->client_id = null;
			// dd('nn');
		} else {
			$shipment->client_id = $request->selectCl['id'];
			// dd( $request->selectCl['id']);
		}
		if ($request->selectD == []) {
			$shipment->driver = '';
			// dd('renn');
		} else {
			$shipment->driver = $request->selectD['id'];
			// dd( $request->selectD['id']);
		}

		if ($request->selectB == []) {
			$shipment->branch_id = Auth::user()->branch_id;
			// dd(Auth::user()->branch_id);
		} else {
			$shipment->branch_id = $request->selectB['id'];
			// dd( $request->selectB['id']);
		}

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

		// if ($request->model) {
		// 	$shipment->client_id = $request->model;
		// 	$sender = User::find($request->model);
		// 	$shipment->sender_name = $sender->name;
		// 	$shipment->sender_email = 'info@speedballcourier.com';
		// 	$shipment->sender_phone = '+254728492446';
		// 	$shipment->sender_address = '17254 00100';
		// 	$shipment->sender_city = $sender->city;
		// } else {
		// 	$shipment->sender_name = 'Speedball Courier';
		// 	$shipment->sender_email = 'info@speedballcourier.com';
		// 	$shipment->sender_phone = '+254728492446';
		// 	$shipment->sender_address = '17254 00100';
		// 	$shipment->sender_city = 'Nairobi';
		// }

		$shipment->sender_name = $request->form['sender_name'];
		$shipment->sender_email = $request->form['sender_email'];
		$shipment->sender_phone = $request->form['sender_phone'];
		$shipment->sender_address = $request->form['sender_address'];
		$shipment->sender_city = $request->form['sender_city'];
		
		// return $request->form['customer_id'];
		$shipment->user_id = Auth::id();
		$shipment->shipment_id = random_int(1000000, 9999999);
		// $shipment->branch_id = Auth::user()->branch_id;

		$users = $this->getAdmin();
		if ($shipment->save()) {
			$shipment->products()->saveMany($products);
		}
		$type = 'shipment';
		Notification::send($users, new ShipmentNoty($shipment, $type));
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
	public function update(Request $request, $id)
	{
		// if ($products->isEmpty()) {
		// 	return response()->json([
		// 		'product_empty' => ['One or more products is required'],
		// 	], 422);
		// }
		$shipment = Shipment::find($id);
		if ($request->selectCl == []) {
			// $shipment->client_id = null;
			// dd('nn');
		} else {
			$shipment->client_id = $request->selectCl['id'];
			// dd( $request->selectCl['id']);
		}
		if ($request->selectD == []) {
			// $shipment->driver = '';
			// dd('renn');
		} else {
			$shipment->driver = $request->selectD['id'];
			// dd( $request->selectD['id']);
		}

		if ($request->selectB == []) {
			// $shipment->branch_id = Auth::user()->branch_id;
			// dd(Auth::user()->branch_id);
		} else {
			$shipment->branch_id = $request->selectB['id'];
			// dd( $request->selectB['id']);
		}

		// $shipment->sub_total = $products->sum('total');
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

		// if ($request->model) {
			// $shipment->sender_name = $request->form['sender_name'];
			// $shipment->sender_email = $request->form['sender_email'];
			// $shipment->sender_phone = $request->form['sender_phone'];
			// $shipment->sender_address = $request->form['sender_address'];
			// $shipment->sender_city = $request->form['sender_city'];
		// } else {
		$shipment->sender_name = $request->form['sender_name'];
		$shipment->sender_email = $request->form['sender_email'];
		$shipment->sender_phone = $request->form['sender_phone'];
		$shipment->sender_address = $request->form['sender_address'];
		$shipment->sender_city = $request->form['sender_city'];
		// }
		
		// return $request->form['customer_id'];
		$shipment->user_id = Auth::id();
		$shipment->shipment_id = random_int(1000000, 9999999);
		// $shipment->branch_id = Auth::user()->branch_id;
		$shipment->save();
		$users = $this->getAdmin();
		// if ($shipment->save()) {
		// 	$shipment->products()->saveMany($products);
		// }
		Notification::send($users, new ShipmentNoty($shipment));
		// $users->notify(new ShipmentNoty($shipment));
		return $shipment;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Shipment  $shipment
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Shipment $shipment)
	{
		Shipment::find($shipment->id)->delete();
	}


	public function getAdmin()
	{
		$users = User::all();
		$userArr = [];
		foreach ($users as $user) {
			if ($user->hasRole('Admin')) {
				$userArr[] = $user;
			}
		}
		return $userArr;
	}

	public function updateStatus(Request $request, Shipment $shipment, $id)
	{
		// return $request->all();
		$shipment = Shipment::find($request->id);
		$shipment->status = $request->formobg['status'];
		// var_dump($request->formobg['status']); die;
		// $shipment->remark = $request->formobg['remark'];
		$shipment->speciial_instruction = $request->formobg['remark'];
		if ($request->formobg['status'] == 'Scheduled') {
			$shipment->derivery_date = $request->formobg['derivery_date'];
			$shipment->derivery_time = $request->formobg['derivery_time'];
		} elseif ($request->formobg['status'] == 'Delivered') {
			$shipment->derivery_date = $request->formobg['derivery_date'];
			$shipment->derivery_time = $request->formobg['derivery_time'];
			$shipment->receiver_id = $request->formobg['receiver_id'];
			$shipment->receiver_name = $request->formobg['receiver_name'];
		}		
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

		if ($request->formobg['status'] == 'Scheduled') {
			$this->send_sms($request->formobg['client_phone'], 'Dear ' . $request->formobg['client_name'] . ', Your shipment has been scheduled to be delivered on ' . $request->formobg['derivery_date']);
		} elseif ($request->formobg['status'] == 'Delivered') {
			$this->send_sms($request->formobg['client_phone'], 'Dear ' . $request->formobg['client_name'] . ', Your shipment has been delivered');
		}
		return $shipment;
	}

	public function UpdateShipment(Request $request, Shipment $shipment)
	{
		// return $request->all();
		$id = [];
		foreach ($request->selected as $selectedItems) {
			$id[] = $selectedItems['id'];
		}
		$status = $request->form['status'];
		$derivery_time = $request->form['derivery_time'];
		$remark = $request->form['remark'];
		// $location = $request->form['location'];
		$derivery_date = $request->form['delivery_date'];
		$shipment = Shipment::setEagerLoads([])->whereIn('id', $id)->update(['status' => $status, 'remark' => $remark, 'derivery_date' => $derivery_date, 'derivery_time' => $derivery_time, 'speciial_instruction' => $remark]);
		$phones = Shipment::setEagerLoads([])->select('id', 'client_phone', 'client_name')->whereIn('id', $id)->get();
		$shipStatus = Shipment::setEagerLoads([])->whereIn('id', $id)->get();
		foreach ($phones as $statuses) {
			$statusUpdate = new ShipmentStatus;
			$statusUpdate->remark = $request->form['remark'];
			$statusUpdate->status = $request->form['status'];
			$statusUpdate->location = $request->form['location'];
			// $statusUpdate->derivery_time = $request->form['derivery_time'];
			$statusUpdate->user_id = Auth::id();
			$statusUpdate->branch_id = Auth::user()->branch_id;
			$statusUpdate->shipment_id = $statuses->id;
			$statusUpdate->save();
		}
		if ($status == 'Scheduled') {
			foreach ($phones as $phone) {
				$this->send_sms($phone->client_phone, 'Dear ' . $phone->client_name . ', Your shipment has been scheduled to be delivered on ' . $derivery_date);
			}
		} elseif ($status == 'Delivered') {
			foreach ($phones as $phone) {
				$this->send_sms($phone->client_phone, 'Dear ' . $phone->client_name . ', Your has been delivered');
			}
		}
		// $shipStatus->statuses()->saveMany($shipStatus);
		return $shipment;
	}

	public function assignDriver(Request $request, Shipment $shipment)
	{
		// return $request->all();
		$id = [];
		foreach ($request->selected as $selectedItems) {
			$id[] = $selectedItems['id'];
		}
		$driver = $request->form['driver'];
		$remark = $request->form['remark'];
		$shipment = Shipment::whereIn('id', $id)->update(['driver' => $driver, 'remark' => $remark]);
		// return $shipment;
	}

	public function assignBranch(Request $request, Shipment $shipment)
	{
		// return $request->all();
		$id = [];
		foreach ($request->selected as $selectedItems) {
			$id[] = $selectedItems['id'];
		}
		$branch = $request->form['branch_id'];
		$remark = $request->form['remark'];
		$shipment = Shipment::whereIn('id', $id)->update(['branch_id' => $branch, 'remark' => $remark]);
		// return $shipStatus = Shipment::whereIn('id', $id)->get();

	}
	public function betweenShipments(Request $request)
	{
		if (Auth::user()->hasRole('Client')) {
			return Shipment::latest()->where('client_id', Auth::id())->take(500)->skip($request->end)->get();
		} else {
			return Shipment::latest()->take(500)->where('country_id', Auth::user()->country_id)->skip($request->end)->get();
		}
		// return Shipment::where('country_id', Auth::user()->country_id)->latest()->skip($request->end)->take(500)->get();
	}

	public function getShipSingle($id)
	{
		return Shipment::find($id);
	}

	public function send_sms($phone, $message)
	{
		// dd($phone . '   ' . $message);
		$phone = '254731090832';
		$sms = 'Test messange';
		$senderID = 'SPEEDBALL';

		$login = 'SPEEDBALL';
		$password = 's12345';

		$clientsmsID = rand(1000, 9999);

		$xml_data = '<?xml version="1.0"?><smslist><sms><user>' . $login . '</user><password>' . $password . '</password><message>' . $sms . '</message><mobiles>' . $phone . '</mobiles><senderid>' . $senderID . '</senderid><clientsmsid>' . $clientsmsID . '</clientsmsid></sms></smslist>';

		$URL = "http://messaging.advantasms.com/bulksms/sendsms.jsp?";

		$ch = curl_init($URL);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);

		// return $output;
	}
}
