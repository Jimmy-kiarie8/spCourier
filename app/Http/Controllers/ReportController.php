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
	public function branchesExpo(Request $request) {
		$date_array = array(
			'start_date' => $request->start_date,
			'end_date' => $request->end_date,
		);
		// $status = $request->status;
		return  Shipment::latest()->setEagerLoads([])->whereBetween('created_at', [$date_array])->take(5000)->where('branch_id',$request->branch_id)->get();
	}

	
	public function displayReport(Request $request) {
		// dd($request->all());
		$date_array = array(
			'start_date' => $request->start_date,
			'end_date' => $request->end_date,
		);
		$status = $request->status;
		return  Shipment::latest()->setEagerLoads([])->whereBetween('created_at', [$date_array])->take(5000)->where('status',$status)->get();

	}
	public function DriverReport(Request $request) {
		// dd($request->all());
		$date_array = array(
			'start_date' => $request->start_date,
			'end_date' => $request->end_date,
		);
		return Shipment::latest()->setEagerLoads([])->whereBetween('created_at', [$date_array])->take(5000)->where('driver',$request->rinder_id)->get();
	}

	public function userDateExpo(Request $request) {
		$date_array = array(
			'start_date' => $request->start_date,
			'end_date' => $request->end_date,
		);
		return Shipment::latest()->where('client_id', $request->client_id)->setEagerLoads([])->whereBetween('created_at', [$date_array])->take(5000)->get();
	}

	public function DelivReport(Request $request)
	{
		// return $request->all();
		$date_array = array(
			'start_date' => $request->start_date,
			'end_date' => $request->end_date,
		);
		$status = $request->status;
		return  Shipment::latest()->setEagerLoads([])->whereBetween('derivery_date', [$date_array])->take(5000)->where('status',$status)->get();
	}
}
