<?php

namespace App\Http\Controllers;

use App\Status;
use Illuminate\Http\Request;

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
    }
}
