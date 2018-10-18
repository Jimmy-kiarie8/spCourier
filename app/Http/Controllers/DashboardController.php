<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Branch;
use App\Shipment;
use Auth;
use DB;
use App\Country;
class DashboardController extends Controller
{
    public function getChartData() {
		// return Shipment::take(100)->get();
		 $shipments = DB::table('shipments')
			->select(DB::raw('count(id) as count, date_format(created_at, "%M") as date'))
			->orderBy('id', 'asc')
			->groupBy('date')
            // ->where('branch_id', Auth::user()->branch_id)
			->get();
        $lables = [];
        $rows = [];
		foreach ($shipments as $key => $shipment) {
            $lables[] = $shipment->date;
			$rows[] = $shipment->count;
        }
        $data = array(
            'lables' => $lables, 
            'rows' => $rows
        );
        return response()->json(['data' => $data]);
    }
    
    public function getChartScheduled() {
		// return Shipment::take(100)->get();
		 $shipments = DB::table('shipments')
			->select(DB::raw('count(id) as count, date_format(created_at, "%M") as date'))
			->orderBy('id', 'asc')
			->groupBy('date')
            ->where('status', 'Scheduled')
			->get();

        $lables = [];
        $rows = [];
		foreach ($shipments as $key => $shipment) {
            $lables[] = $shipment->date;
			$rows[] = $shipment->count;
        }
        $data = array(
            'lables' => $lables, 
            'rows' => $rows
        );
        return response()->json(['data' => $data]);
    }
    
    public function getChartDelivered() {
		// return Shipment::take(100)->get();
		 $shipments = DB::table('shipments')
			->select(DB::raw('count(id) as count, date_format(created_at, "%M") as date'))
			->orderBy('id', 'asc')
			->groupBy('date')
            ->where('status', 'Delivered')
			->get();

        $lables = [];
        $rows = [];
		foreach ($shipments as $key => $shipment) {
            $lables[] = $shipment->date;
			$rows[] = $shipment->count;
        }
        $data = array(
            'lables' => $lables, 
            'rows' => $rows
        );
        return response()->json(['data' => $data]);
	}
    
    public function getChartCancled() {
		// return Shipment::take(100)->get();
		 $shipments = DB::table('shipments')
			->select(DB::raw('count(id) as count, date_format(created_at, "%M") as date'))
			->orderBy('id', 'asc')
			->groupBy('date')
            ->where('status', 'Cancelled')
			->get();

        $lables = [];
        $rows = [];
		foreach ($shipments as $key => $shipment) {
            $lables[] = $shipment->date;
			$rows[] = $shipment->count;
        }
        $data = array(
            'lables' => $lables, 
            'rows' => $rows
        );
        return response()->json(['data' => $data]);
	}
    
    public function getCountCount()
    {
        $countries = ['name' => 'Kenya', 'name' => 'Uganda', 'name' => 'Tanzania'];
        foreach ($countries as $country) {
            // return $country;
            $tanzania = Shipment::where('country', 'Tanzania')->count();
            $uganda = Shipment::where('country', 'Uganda')->count();
            $kenya = Shipment::where('country', 'Kenya')->count();
        }
        return $country = array(
            'Kenya' => $kenya, 
            'Tanzania' => $tanzania, 
            'Uganda' => $uganda, 
        );
    }

    
    public function getBranchCount()
    {
        $branches = Branch::setEagerLoads([])->get();
        $branch_count = [];
        foreach ($branches as $key => $branch) {
            // return $branch->id;
            // $branch_count[] = Shipment::where('branch_id', $branch->id)->count();
            $branch_count[] = array(
                'name' => $branch->branch_name,
                'id' => $key,
                'count' => Shipment::where('branch_id', $branch->id)->count(),
            );
        }
        return $branch_count;
    }
    
    public function getChartBranch() {
		// return Shipment::take(100)->get();
		 $shipments = DB::table('shipments')
			->select(DB::raw('count(id) as count, date_format(created_at, "%M") as date'))
			->orderBy('id', 'asc')
			->groupBy('date')
            ->where('branch_id', Auth::id())
			->get();

        $lables = [];
        $rows = [];
		foreach ($shipments as $key => $shipment) {
            $lables[] = $shipment->date;
			$rows[] = $shipment->count;
        }
        $data = array(
            'lables' => $lables, 
            'rows' => $rows
        );
        return response()->json(['data' => $data]);
    }

    public function getChartCount() {
		// return Shipment::take(100)->get();
		 $shipments = DB::table('shipments')
			->select(DB::raw('count(id) as count, date_format(created_at, "%M") as date'))
			->orderBy('id', 'asc')
			->groupBy('date')
            ->where('branch_id', Auth::id())
			->get();

        $lables = [];
        $rows = [];
		foreach ($shipments as $key => $shipment) {
            $lables[] = $shipment->date;
			$rows[] = $shipment->count;
        }
        $data = array(
            'lables' => $lables, 
            'rows' => $rows
        );
        return response()->json(['data' => $data]);
    }

    public function getBranchShipments()
    {
        $branches = Branch::all();
        $A_branch = [];
        foreach ($branches as $branch) {
            $shipmen_ts = DB::table('shipments')
			->select(DB::raw('count(id) as count, date_format(created_at, "%M") as date, branch_id'))
			->orderBy('id', 'asc')
			->groupBy('date')
            ->where('branch_id', $branch->id)
            ->get();
            
            // $shipments = Shipment::where('branch_id', $branch->id)->count();
            // json_decode(json_encode($branch), true);
            $A_branch[] = array_prepend(json_decode(json_encode($shipmen_ts), true), $branch->branch_name, 'name');
        }
        return $shipmen_ts;
    }
    

    public function getCountryhipments()
    {
        $branches = Country::all();
        $A_country = [];
        foreach ($branches as $branch) {
            $shipmen_ts = DB::table('shipments')
			->select(DB::raw('count(id) as count, date_format(created_at, "%M") as date, country_id'))
			->orderBy('id', 'asc')
			->groupBy('date')
            ->where('country_id', $country->id)
            ->get();
            
            // $shipments = Shipment::where('branch_id', $branch->id)->count();
            // json_decode(json_encode($branch), true);
            $A_country[] = array_prepend(json_decode(json_encode($shipmen_ts), true), $branch->branch_name, 'name');
        }
        return $shipmen_ts;
    }
}
