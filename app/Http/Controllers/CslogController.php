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
        $shipments = Shipment::setEagerLoads(['statuses'])->select('id')->whereBetween('created_at', [$today, $tomorrow])->get();


        foreach ($shipments as $shipment) {
            $id[] = $shipment->id;
        }



        // $sh_count = Shipment::whereBetween('created_at', ['2019-01-29 00:00:00', '2019-01-30 00:00:00'])->count();
        // $id = [];
        
        // // return array_flatten($shipments);
        
        // foreach ($shipments as $shipment) {
        //     $id[] = $shipment->id;
        // }
        // // return ShipmentStatus::whereBetween('created_at', ['2019-01-29 00:00:00', '2019-01-30 00:00:00'])->whereIn('shipment_id', $id)->count();
        // $users = User::setEagerLoads([])->get();
        // $user_count = [];
        //     foreach ($users as $key => $user) {
        //     // return user->id;
        //     // $user_count[] = Shipment::where('branch_id', user->id)->count();
        //     if ($user->hasRole('Customer Service') && Auth::id() == $user->country_id) {
        //         $ship_count = ShipmentStatus::whereBetween('created_at', [$today, $tomorrow])->whereIn('shipment_id', $id)->where('user_id', $user->id)->groupBy('user_id')->count();
        //         $user_count[] = array(
        //             'cou' => ShipmentStatus::whereBetween('created_at', [$today, $tomorrow])->where('user_id', $user->id)->whereIn('shipment_id', $id)->groupBy('user_id')->count() . $user->name,
        //             'name' => $user->name,
        //             'id' => $key,
        //             'count' => (ShipmentStatus::whereIn('shipment_id', $id)->where('user_id', $user->id)->where('status', 'Scheduled')->groupBy('user_id')->count() / $ship_count) * 100,
        //             // 'calls' => (ShipmentStatus::whereIn('shipment_id', $id)->where('user_id', $user->id)->where('status', 'Scheduled')->count() / $sh_count) * 100,
        //         );
        //     }
        // }
        // return $user_count;
    }
}
