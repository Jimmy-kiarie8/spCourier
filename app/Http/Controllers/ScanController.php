<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shipment;
use App\ShipmentStatus;
use Auth;
use Illuminate\Support\Carbon;

class ScanController extends Controller
{
	// Out Scan
	public function barcodeUpdate(Request $request, Shipment $shipment, $bar_code = null)
	{
		// return $request->all();
		$bar_code = str_replace("-", "", $request->bar_code_out);
		$barcode = Shipment::where('bar_code', 'LIKE', "%{$bar_code}%")->first();
		if ($barcode) {
			return $barcode;
		} else {
			return response()->json(['errors' => 'errors']);
		}
	}


	public function statusUpdate(Request $request)
	{
		// return $request->all();
		// $this->validate($request, [
		// 	'form.status_out' => 'required',
		// 	'form.rider_out' => 'required',
			
		// ]);
		$id = [];
		foreach ($request->scan as $selectedItems) {
			$id[] = $selectedItems['id'];
		}
        // return $id;
		$status = $request->form['status_out'];
		// $derivery_time = $request->derivery_time;
		$remark = $request->form['remarks_out'];
		$rider_out = $request->form['rider_out'];
		$location = $request->form['location_out'];
		$assign_date = date("Y-m-d");
		$scan_date_out = $request->form['scan_date_out'];
		// dd($assign_date);
		$shipment = Shipment::whereIn('id', $id)->update(['status' => $status, 'remark' => $remark, 'driver' => $rider_out, 'assign_date' => $assign_date, 'derivery_date' => $scan_date_out]);
		$shipStatus = Shipment::whereIn('id', $id)->get();
		foreach ($shipStatus as $statuses) {
			$statusUpdate = new ShipmentStatus;
			$statusUpdate->remark = $remark;
			$statusUpdate->status = $status;
			$statusUpdate->location = $location;
			// $statusUpdate->derivery_time = $request->derivery_time;
			$statusUpdate->user_id = Auth::id();
			$statusUpdate->branch_id = Auth::user()->branch_id;
			$statusUpdate->shipment_id = $statuses->id;
			// return $statusUpdate;
			$statusUpdate->save();
			// return $statusUpdate;
		}
		return $shipment;

	}


	// In Scan
	public function barcodeIn(Request $request, Shipment $shipment, $bar_code_in = null)
	{
		$bar_code = str_replace("-", "", $request->bar_code_in);
		// dd($barcode);
		$barcode = Shipment::where('bar_code', 'LIKE', "%{$bar_code}%")->first();
		if ($barcode) {
			return $barcode;
		} else {
			return response()->json(['errors' => 'errors']);
		}
	}

	public function statusUpdateIn(Request $request)
	{
		// return $request->all();
		$this->validate($request, [
			'form.status_in' => 'required',
			'form.client_id' => 'required',

		]);
		$id = [];
		foreach ($request->scan as $selectedItems) {
			$id[] = $selectedItems['id'];
		}
        // return $id;
		$status = $request->form['status_in'];
		// $derivery_time = $request->derivery_time;
		$remark = $request->form['remarks_in'];
		$client_id = $request->form['client_id'];
		$location = $request->form['location_in'];
		// $derivery_date = $request->scheduled_date;
		$shipment = Shipment::whereIn('id', $id)->update(['status' => $status, 'remark' => $remark, 'driver' => $client_id]);
		$shipStatus = Shipment::whereIn('id', $id)->get();
		foreach ($shipStatus as $statuses) {
			$statusUpdate = new ShipmentStatus;
			$statusUpdate->remark = $remark;
			$statusUpdate->status = $status;
			$statusUpdate->location = $location;
			// $statusUpdate->derivery_time = $request->derivery_time;
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

	public function filterR(Request $request)
	{
		$driver = $request->selectRider['id'];
		$start_date = $request->form['start_date'];
		$end_date = $request->form['end_date'];
		return Shipment::where('driver', $driver)->whereBetween('assign_date', [$start_date, $end_date])->take(500)->latest()->get();
	}

	public function getDelScan(Request $request)
	{
		$driver = $request->selectRider['id'];
		$start_date = $request->form['start_date'];
		$end_date = $request->form['end_date'];
		return Shipment::where('driver', $driver)->where('status', 'Delivered')->whereBetween('assign_date', [$start_date, $end_date])->take(500)->latest()->count();
	}

	public function getNotDelScan(Request $request)
	{
		$driver = $request->selectRider['id'];
		$start_date = $request->form['start_date'];
		$end_date = $request->form['end_date'];
		return Shipment::where('driver', $driver)->where('status', '!=', 'Delivered')->whereBetween('assign_date', [$start_date, $end_date])->take(500)->latest()->count();
	}
}
