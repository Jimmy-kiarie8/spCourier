<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shipment;
use Illuminate\Support\Carbon;
use App\ShipmentStatus;

class CslogController extends Controller
{
    public function schedulepct(Request $request)
    {
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();
        $shipments = Shipment::select('id')->whereBetween('created_at', [$today, $tomorrow])->get();
        $id = [];
        
        // return array_flatten($shipments);
        
        foreach ($shipments as $shipment) {
            $id[] = $shipment->id;
        }
        // return array_flatten($id);
        $ship_status = ShipmentStatus::whereIn('shipment_id', $id)->get();
        return $ship_status;
    }
}
