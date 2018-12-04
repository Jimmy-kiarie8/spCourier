<?php

namespace App\Http\Controllers;

use App\Status;
use Illuminate\Http\Request;
use App\Shipment;
use Illuminate\Support\Facades\Auth;

class StatusController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        $status = new Status;
        $status->name = $request->name;
        $status->save();
        return $status; 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Status $status)
    {
        // return $request->all();
        $status = Status::find($request->id);
        $status->name = $request->name;
        $status->save();
        return $status; 
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function destroy(Status $status)
    {
        //
    }

    
    public function getStatuses()
    {
        return Status::select('name')->orderBy('name', 'ASC')->get();
    }
    public function getStat()
    {
        return Status::orderBy('name', 'ASC')->get();
        // return response()->json(Status::orderBy('name', 'ASC')->get(), 200);
        // return response()->json(array(Status::orderBy('name', 'ASC')->get())), 200);
        // return response()->json(array('success' => 'Log In Successful'), 200);
    }

	public function scheduled()
	{
		$date1 = Carbon::today();
		$date2 = new Carbon('tomorrow');
		$shipment = Shipment::setEagerLoads([])->where('status', 'Scheduled')->whereBetween('derivery_date', [$date1, $date2])->take(300)->get();
		return $shipment;
	}

	public function getScheduled(Request $request)
	{
		// return $request->all();
		$print_shipment = Shipment::where('status', 'Scheduled')->whereBetween('derivery_date', [$request->start_date, $request->end_date])->where('printed', 0)->where('country_id', Auth::user()->country_id)->take(500)->latest()->get();
		$id = [];
		foreach ($print_shipment as $selectedItems) {
			$id[] = $selectedItems['id'];
		}
		$status = $request->form['status'];
		$derivery_time = $request->form['derivery_time'];
		$remark = $request->form['remark'];
		$derivery_date = $request->form['scheduled_date'];
		$shipment = Shipment::whereIn('id', $id)->update(['printed' => 1]);
		return $print_shipment;
    }
    
	public function getDeriveredA()
	{
		return Shipment::where('status', 'Delivered')->latest()->take(500)->get();
	}
}
