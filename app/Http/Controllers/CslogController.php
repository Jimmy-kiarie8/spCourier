<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Shipment;
use Illuminate\Support\Carbon;
use App\ShipmentStatus;
use App\User;

class CslogController extends Controller
{
    public function schedulepct(Request $request)
    {
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();
        $shipments = Shipment::setEagerLoads([])->select('id')->whereBetween('created_at', [$today, $tomorrow])->get();
        $sh_count = Shipment::count();
        $id = [];
        
        // return array_flatten($shipments);
        
        foreach ($shipments as $shipment) {
            $id[] = $shipment->id;
        }
        $users = User::setEagerLoads([])->get();
        $user_count = [];
            foreach ($users as $key => $user) {
            // return user->id;
            // $user_count[] = Shipment::where('branch_id', user->id)->count();
                $user_count[] = array(
                    'name' => $user->name,
                    'id' => $key,
                    'count' => (ShipmentStatus::whereIn('shipment_id', $id)->where('user_id', $user->id)->where('status', 'Scheduled')->count() / $sh_count) * 100,
                    // 'calls' => (ShipmentStatus::whereIn('shipment_id', $id)->where('user_id', $user->id)->where('status', 'Scheduled')->count() / $sh_count) * 100,
                );
            }
        return $user_count;
    }
}
