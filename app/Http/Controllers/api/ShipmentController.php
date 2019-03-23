<?php

namespace App\Http\Controllers\Api;

use App\Shipment;
use App\Pincode;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\UserController as ApiUser;
use App\User;
use Illuminate\Support\Carbon;
use App\Product;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Notifications\ShipmentNoty;
use Notification;

class ShipmentController extends Controller
{
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */


	public function store(Request $request)
	{
		
		$api_user = new ApiUser();
		$user_data = $api_user->login($request);
		
		if (empty($user_data) || isset($user_data['error'])) {
			return response()->json(['error'=>'Unauthorised User','status' => '401'], 401);
		}
		$user_id = !empty($user_data['id']) ? $user_data['id'] : '';
		$branch_id = !empty($user_data['branch_id']) ? $user_data['branch_id'] : '';
		$products = collect($request->form['products'])->transform(function ($product) use ($user_id) {
			$product['total'] = $product['quantity'] * $product['price'];
			$product['user_id'] = $user_id;
			return new Product($product);
		});

		if ($products->isEmpty()) {
			return response()->json([
				'product_empty' => ['One or more products is required'],
			], 422);
		}
		$shipment = new Shipment;
		if ($request->selectCl == []) {
			$shipment->client_id = null;
		} else {
			$shipment->client_id = $request->selectCl['id'];
		}
		if ($request->selectD == []) {
			$shipment->driver = '';
		} else {
			$shipment->driver = $request->selectD['id'];
		}

		if ($request->selectB == []) {
			$shipment->branch_id = Auth::user()->branch_id;
		} else {
			$shipment->branch_id = $request->selectB['id'];
		}

		$shipment->sub_total = $products->sum('total');
		$shipment->client_name = $request->form['client_name'];
		$shipment->client_phone = $request->form['client_phone'];
		$shipment->client_email = $request->form['client_email'];
		$shipment->client_address = $request->form['client_address'];
		$shipment->client_city = $request->form['client_city'];
		$shipment->airway_bill_no = $request->form['bar_code'];
		$shipment->shipment_type = $request->form['shipment_type'];
		$shipment->payment = $request->form['payment'];
		$shipment->insuarance_status = $request->form['insuarance_status'];
		$shipment->status = $request->form['status'];
		$shipment->booking_date = $request->form['booking_date'];
		$shipment->derivery_date = $request->form['derivery_date'];
		$shipment->derivery_time = $request->form['derivery_time'];
		$shipment->bar_code = $request->form['bar_code'];
		$shipment->to_city = $request->form['to_city'];
		$shipment->cod_amount = $request->form['cod_amount'];
		$shipment->from_city = $request->form['from_city'];

		if ($request->model) {
			$shipment->client_id = $request->model;
			$sender = User::find($request->model);
			$shipment->sender_name = $sender->name;
			$shipment->sender_email = 'info@speedballcourier.com';
			$shipment->sender_phone = '+254728492446';
			$shipment->sender_address = '17254 00100';
			$shipment->sender_city = $sender->city;
		} else {
			$shipment->sender_name = 'Speedball Courier';
			$shipment->sender_email = 'info@speedballcourier.com';
			$shipment->sender_phone = '+254728492446';
			$shipment->sender_address = '17254 00100';
			$shipment->sender_city = 'Nairobi';
		}
		
		$shipment->user_id = $user_id;
		$shipment->shipment_id = random_int(1000000, 9999999);

		$users = $this->getAdmin();
		if ($shipment->save()) {
			$shipment->products()->saveMany($products);
		}
		// Notification::send($users, new ShipmentNoty($shipment));
		return response()->json(['success' => $shipment, 'status' => '200'], '200'); 
		die();
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
	
	public function delete(Request $request) {
		$api_user = new ApiUser();
		$user_data = $api_user->login($request);

		if (empty($user_data) || isset($user_data['error'])) {
			return response()->json(['error'=>'Unauthorised User','status' => '401'], 401);
		}

		if (empty($user_data['id']) || empty($request->get('awb_no')) ) {
			return response()->json(['error'=>'Required Parameter Missing','status' => '400'], 400);
		}
		
		$data = Shipment::where(['user_id' => $user_data['id'], 'airway_bill_no' => $request->get('awb_no')])->select()->get()->toArray();
		if (empty($data)) {
			return response()->json(['error'=>'Awb Number Does not exsist','status' => '200'], 200);
		}
		
		Shipment::where(['user_id' => $user_data['id'], 'airway_bill_no' => $request->get('awb_no')])->delete();

		return response()->json(['success'=> 'Tracking number sucessfully deleted','status' => '200'], 200);
	}

	public function track(Request $request) {
		$api_user = new ApiUser();
		$user_data = $api_user->login($request);

		if (empty($user_data) || isset($user_data['error'])) {
			return response()->json(['error'=>'Unauthorised User','status' => '401'], 401);
		}

		if (empty($user_data['id']) || empty($request->get('awb_no')) ) {
			return response()->json(['error'=>'Required Parameter Missing', 'status' => '400'], 400);
		}
		
		$data = Shipment::where(['user_id' => $user_data['id'], 'airway_bill_no' => $request->get('awb_no')])->select()->get()->first();
		if (empty($data)) {
			return response()->json(['error'=>'Awb Number Does not exsist', 'status' => '200'], 200);
		}
		
		$status = !empty($data['status']) ? $data['status'] : 'No Status Found'; 
		return response()->json(['success'=> $status, 'status' => '200'], 200);
	}

	public function pincode(Request $request) {
		return $request->all();
		$api_user = new ApiUser();
		$user_data = $api_user->login($request);
		if (empty($user_data) || isset($user_data['error'])) {
			return response()->json(['error'=>'Unauthorised User', 'status' => '401'], 401);
		}

		if (empty($request->get('pincode')) ) {
			return response()->json(['error'=>'Required Parameter Missing', 'status' => '400'], 400);
		}
		

		$pincode = Pincode::where(['pincode' => $request->get('pincode')])->select('cod', 'prepaid')->get()->first();

		if (empty($pincode)) {
			return response()->json(['error'=> 'Pincode is not Available', 'status' => '200'], 200);	
		}

		$pincode_data = array();
		$pincode_data['cod'] 	 = !empty($pincode['cod']) ? $pincode['cod'] : 0;  
		$pincode_data['prepaid'] = !empty($pincode['prepaid']) ? $pincode['prepaid'] : 0;

		return response()->json(['success'=> $pincode_data, 'status' => '200'], 200);
	}

	public function createPickup(Request $request) {

		$api_user  = new ApiUser();
		$user_data = $api_user->login($request);
		$response  = array();
		if (empty($user_data) || isset($user_data['error'])) {
			return response()->json(['error'=>'Unauthorised User', 'status' => '401'], 401);
		}

		if (empty($request->get('request'))) {
			return response()->json(['error'=>'Required Parameter Missing', 'status' => '400'], 400);
		}
		foreach ($request->get('request') as $key => $value) {

			$response[$key]['status'] = 0;

			if (empty($value['awb_no']) || empty($value['pickup_at'])) {
				continue;
			}	

			$response[$key]['awb_no'] = $value['awb_no'];

			$data = Shipment::where(['user_id' => $user_data['id'], 'airway_bill_no' => $value['awb_no']])->select()->get()->first();
			
			if (empty($data)) {
				continue;
			}elseif(!empty($data['pickup_id'])) {
				$response[$key]['pickup_id'] = $data['pickup_id'];
				continue;
			}

			$pickup_time = strtotime($value['pickup_at']);

			$pickup_id = Shipment::max('pickup_id');
			$pickup_id = !empty($pickup_id) ? $pickup_id + 1 : 1; 

			Shipment::where(['user_id' => $user_data['id'], 'airway_bill_no' => $value['awb_no']])->update(['pickup_id' => $pickup_id, 'pickup_at' => $pickup_time]);

			$response[$key]['pickup_id'] = $pickup_id;
			$response[$key]['status'] = 1;
		}

		return response()->json(['success'=> json_encode($response), 'status' => '200'], 200);
	}

	public function deletePickup(Request $request) {

		$api_user  = new ApiUser();
		$user_data = $api_user->login($request);
		$response  = array();
		if (empty($user_data) || isset($user_data['error'])) {
			return response()->json(['error'=>'Unauthorised User', 'status' => '401'], 401);
		}

		if (empty($request->get('awb_no'))) {
			return response()->json(['error'=>'Required Parameter Missing', 'status' => '400'], 400);
		}

		$data = Shipment::where(['user_id' => $user_data['id'], 'airway_bill_no' => $request->get('awb_no')])->select()->get()->first();
		if (empty($data)) {
			return response()->json(['error'=>'Awb Number Does not exsist', 'status' => '200'], 200);
		}

		Shipment::where(['user_id' => $user_data['id'], 'airway_bill_no' => $request->get('awb_no')])->update(['pickup_id' => '', 'pickup_at' => '']);

		return response()->json(['success'=> 'Sucessfully Deleted', 'status' => '200'], 200);
	}


}
