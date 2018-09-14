<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shipment;
use App\ShipmentStatus;
use Auth;
class ScanController extends Controller
{
	public function barcodeUpdate(Request $request, Shipment $shipment, $bar_code = null) {
		// return $request->all();
		$barcode = Shipment::where('bar_code', $request->bar_code)->first();
		if ($barcode) {
			return $barcode;
		}else{
			return response()->json(['errors'=>'This Shipment Does not exist in our records']);
		}
	}

    
    public function statusUpdate(Request $request)
    {
		// return $request->all();
        $id = [];
		foreach ($request->scan as $selectedItems ) {
			$id[] = $selectedItems['id'];
        }
        // return $id;
		$status = $request->form['status_out'];
		// $derivery_time = $request->derivery_time;
		$remark = $request->form['remarks_out'];
		$location = $request->form['location'];
		// $derivery_date = $request->scheduled_date;
		$shipment = Shipment::whereIn('id', $id)->update(['status' => $status, 'remark' => $remark]);
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

	public function barcodeIn(Request $request, Shipment $shipment, $bar_code_in = null) {
		$barcode = Shipment::where('bar_code', $request->bar_code_in)->first();
		if ($barcode) {
			return $barcode;
		}else{
			return response()->json(['errors'=>'This Shipment Does not exist in our records']);
		}
    }
    
    public function statusUpdateIn(Request $request)
    {
		// return $request->all();
        $id = [];
		foreach ($request->scan as $selectedItems ) {
			$id[] = $selectedItems['id'];
        }
        // return $id;
		$status = $request->form['status_in'];
		// $derivery_time = $request->derivery_time;
		$remark = $request->form['remarks_in'];
		$location = $request->form['location'];
		// $derivery_date = $request->scheduled_date;
		$shipment = Shipment::whereIn('id', $id)->update(['status' => $status, 'remark' => $remark]);
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
}
