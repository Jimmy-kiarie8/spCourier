<?php

namespace App\Http\Controllers;

use App\Shipment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use PdfReport;
use ExcelReport;
use Illuminate\Support\Carbon;
use App\Exports\Reports;
// use Maatwebsite\Excel\Exporter;
// use Maatwebsite\Excel\Facades\Excel;
use PDF;
use App\Mail\ReportMail;


class ReportController extends Controller {
	
	public function index() {
		$clients = shipment::where('branch_id', Auth::user()->branch_id)->get();
		// $customers = User::where('branch_id', Auth::user()->branch_id)->get();
		$users = User::with('roles')->get();
		$userArr = [];
		foreach ($users as $user) {
			foreach ($user->roles as $role) {
				if ($role->name == 'Customer') {
					$userArr[] = $role->pivot->user_id;		
				}
			}
		}
		$drivers = $this->getDrivers();
		
		$customers = User::whereIn('id', $userArr)->get();
		// return json_decode(json_encode($customers));
		return view('reports.index', compact('customers', 'clients', 'drivers'));
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

	public function userDateExpo(Request $request) {
		// var_dump($request->all());die;
		// var_dump(Shipment::whereBetween('created_at', [$request->start_date, $request->end_date])->where('client_name', $request->name)->get());die;
		$date_array = array(
			'start_date' => $request->start_date,
			'end_date' => $request->end_date,
		);
		$client_id = $request->name;
		$columns = [
			'order_id',
			'sender_name',
			'sender_email',
			'sender_phone',
			'sender_city',
			'sender_address',
			'driver',
			'client_name',
			'client_email',
			'client_phone',
			'client_city',
			'client_address',
			'derivery_status',
			'amount_ordered',
			'booking_date',
		];

		$columns_name = [
			'order_id As Order Id',
			'sender_name As Sender Name',
			'sender_email As Sender Email',
			'sender_phone As Sender Phone',
			'sender_city As Sender City',
			'sender_address As Sender Address',
			'driver As Driver',
			'client_name As Client Name',
			'client_email As Client Email',
			'client_phone As Client Phone',
			'client_city As Client City',
			'client_address As Client Address',
			'derivery_status As Derivery Status',
			'amount_ordered As Quantity',
			'booking_date As Booking Date',
		];
		$query = Shipment::select($columns)->setEagerLoads([])->get();
		$results = Excel::create('Shipment', function ($excel) use ($query) {
			$excel->sheet('Sheetname', function ($sheet) use ($query) {
				$sheet->fromArray($query);
			});

		})->download('xls');
		return redirect('/shipreports');
	}
	public function shipmentExpo(Request $request) {
		// var_dump($request->id); die;
		$results = Excel::create('Shipment', function ($excel) {

			$excel->sheet('Sheetname', function ($sheet) {
				
			$columns = [
				'order_id',
				'sender_name',
				'sender_email',
				'sender_phone',
				'sender_city',
				'sender_address',
				'driver',
				'client_name',
				'client_email',
				'client_phone',
				'client_city',
				'client_address',
				'derivery_status',
				'amount_ordered',
				'booking_date',
			];
				// $model =
				$sheet->fromModel(Shipment::select($columns)->setEagerLoads([])->get());

			});

		})->download('xls');
		return redirect('/shipreports');
		// return;
	}
	public function userExpo() {
		$results = Excel::create('Users', function ($excel) {

			$excel->sheet('Sheetname', function ($sheet) {

				$sheet->fromModel(User::all());

			});

		})->export('xls');
		return redirect('/shipreports');
		// return;
	}
	public function deriverdExpo() {
		$results = Excel::create('Users', function ($excel) {

			$excel->sheet('Sheetname', function ($sheet) {

				$sheet->fromModel(Shipment::where('status', 'Derivered')->get());

			});

		})->export('xls');
		return redirect('/shipreports');
	}
	public function customersExpo() {
		$results = Excel::create('Users', function ($excel) {

			$excel->sheet('Sheetname', function ($sheet) {

				$sheet->fromModel(User::where('type', 'customer')->get());

			});

		})->export('xls');
		return redirect('/shipreports');
	}
	public function dispatchedExpo() {
		$results = Excel::create('Users', function ($excel) {

			$excel->sheet('Sheetname', function ($sheet) {

				$sheet->fromModel(Shipment::where('status', 'Dispatched')->get());

			});

		})->export('xls');
		return redirect('/shipreports');

	}
	public function pendingExpo() {
		$results = Excel::create('Users', function ($excel) {

			$excel->sheet('Sheetname', function ($sheet) {

				$columns = [
					'order_id',
					'sender_name',
					'sender_email',
					'sender_phone',
					'sender_city',
					'sender_address',
					'driver',
					'client_name',
					'client_email',
					'client_phone',
					'client_city',
					'client_address',
					'derivery_status',
					'amount_ordered',
					'booking_date',
				];
				$sheet->fromModel(Shipment::select($columns)->where('status', 'pending')->get());

			});

		})->export('xls');
		return redirect('/shipreports');
	}
	public function bookingExpo() {

	}
	public function approvedExpo() {
		$results = Excel::create('Approved Shipments', function ($excel) {

			$excel->sheet('Sheetname', function ($sheet) {

				$columns = [
					'order_id',
					'sender_name',
					'sender_email',
					'sender_phone',
					'sender_city',
					'sender_address',
					'driver',
					'client_name',
					'client_email',
					'client_phone',
					'client_city',
					'client_address',
					'derivery_status',
					'amount_ordered',
					'booking_date',
				];
				$sheet->fromModel(Shipment::select($columns)->where('status', 'approved')->get());

			});

		})->export('xls');
		return redirect('/shipreports');

	}

	// PdfReport Aliases

public function dsplayReport(Request $request) {
		// return Excel::download(new Reports, 'reports.xlsx');
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
	$next_month = $today->addMonth();

	$toDate = '2018-08-17';
	$sortBy = 'id';

	// Report title
	$title = 'Registered User Report';

	// For displaying filters description on header
	$meta = [
		'Report From' => $fromDate . ' To ' . $toDate,
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
		return $customer->only('email', 'name');
	});

	foreach ($cust_emails as $mails) {
	$email = $mails['email'];
		
	// Do some querying..
	$queryBuilder = Shipment::whereBetween('created_at', ['2018-08-01' ,$next_month])
						->where('client_name', $mails['name'])
						->orderBy($sortBy);
	$columns = [
		'airway bill no',
		'sender name',
		'sender email',
		'sender city',
		'sender phone',
		'client name',
		'client email',
		'client city',
		'client phone',
		'amount ordered',
		'derivery date',
	];
	
	$pdf = Excel::create($title, $meta, $queryBuilder, $columns)
					// ->editColumn('created at', [
					// 	'displayAs' => function($result) {
					// 		return $result->created_at->format('d M Y');
					// 	}
					// ])
					// ->setCss([
					// 	'.head-content' => 'border-width: 1px',
					//  ])
					// ->limit(10)
					// ->stream(); // or download('f
					->export('xls');
					return $pdf;

	}	
	}

	
	public function displayReport(Request $request) {
		// var_dump($request->all());die;
		$date_array = array(
			'start_date' => $request->start_date,
			'end_date' => $request->end_date,
		);
		$status = $request->status;
		$query = Shipment::setEagerLoads([])->whereBetween('created_at', [$date_array])->where('status',$status)->get();
		// var_dump($query);die;
		$results = Excel::create('Shipment', function ($excel) use ($query) {
			$excel->sheet('Sheetname', function ($sheet) use ($query) {
				$sheet->fromArray($query);
			});

		})->download('xls');
		return redirect('/shipreports');
	}
	public function DriverReport(Request $request) {
		// var_dump($request->all());die;
		$date_array = array(
			'start_date' => $request->start_date,
			'end_date' => $request->end_date,
		);
		$status = $request->status;
		$query = Shipment::setEagerLoads([])->whereBetween('created_at', [$date_array])->where('status',$status)->get();
		// var_dump($query);die;
		$results = Excel::create('Shipment', function ($excel) use ($query) {
			$excel->sheet('Sheetname', function ($sheet) use ($query) {
				$sheet->fromArray($query);
			});

		})->download('xls');
		return redirect('/shipreports');
	}

	public function pod(Request $request, $id)
	{
		// return;
		// echo 'qppqpq';
		$shipments = Shipment::find($id);
		// view()->share('shipments	',$shipments);
		// return $shipments;
        // if($request->has('download')){
			$pdf = PDF::loadView('reports.pod', compact('shipments'));
            return $pdf->download('reports.pdf');
        // }
		return view('reports.pod', compact('shipments'));
	}


	public function generate_pdf() {
		$data = [
			'foo' => 'bar'
		];
		$pdf = PDF::loadView('home', $data);
		return $pdf->stream('document.pdf');
	}
}
