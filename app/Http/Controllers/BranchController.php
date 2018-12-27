<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Shipment;
use App\Http\Requests\BranchesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
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
		$this->Validate($request, [
			'email' => 'required|email',
			'phone' => 'required|numeric',
			'address' => 'required',
			'branch_name' => 'required',
		]);
		$branch = new Branch;
		$branch->branch_name = $request->branch_name;
		$branch->phone = $request->phone;
		$branch->address = $request->address;
		$branch->email = $request->email;
		$branch->user_id = Auth::id();
		$branch->branch_id = Auth::user()->branch_id;
		$branch->save();
		return $branch;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Branch  $branch
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Branch $branch)
	{
		// return $request->all();
		$branch = Branch::find($request->id);
		$branch->branch_name = $request->branch_name;
		$branch->phone = $request->phone;
		$branch->address = $request->address;
		$branch->email = $request->email;
		$branch->save();
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Branch  $branch
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Branch $branch)
	{
        Branch::find($branch->id)->delete();
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getBranch()
	{
		return Branch::all();
	}

	public function getBranchShip(Request $request, $id)
	{
		return Branch::where('id', $id)->get();
	}

	public function getBranchEger()
	{
		return Branch::setEagerLoads([])->get();
	}

	public function getBranchC()
	{
		return Branch::count();
	}

	public function getShipBranch(Request $request)
	{
		// return $request->all();
		if($request->selectCl) {
			// return 'test';
			return Shipment::where('client_id', $request->selectCl['id'])->take(500)->latest()->get();
		}
		if ($request->form['start_date'] == '' || $request->form['end_date'] == '') {
			if ($request->select['id'] == 'all') {
				if ($request->selectStatus['state'] == 'All') {
					if ($request->selectAss['Assigned'] == 'All') {
						return Shipment::whereNotNull('branch_id')->take(500)->latest()->get();
					} else {
						if ($request->selectAss['Assigned'] == 'Assigned') {
							return Shipment::whereNotNull('branch_id')->take(500)->latest()->get();
						} else {
							return Shipment::whereNull('branch_id')->take(500)->latest()->get();
						}
					}
				} else {
					if ($request->selectAss['Assigned'] == 'All') {
						return Shipment::whereNotNull('branch_id')->where('status', $request->selectStatus['state'])->take(500)->latest()->get();
					} else {
						if ($request->selectAss['Assigned'] == 'Assigned') {
							return Shipment::whereNotNull('branch_id')->where('status', $request->selectStatus['state'])->take(500)->latest()->get();
						} else {
							return Shipment::whereNull('branch_id')->where('status', $request->selectStatus['state'])->take(500)->latest()->get();
						}
					}
				}

			} else {
				if ($request->selectStatus['state'] == 'All') {
					if ($request->selectAss['Assigned'] == 'All') {
						return Shipment::whereNotNull('branch_id')->where('branch_id', $request->select['id'])->take(500)->latest()->get();
					} else {
						if ($request->selectAss['Assigned'] == 'Assigned') {
							return Shipment::whereNotNull('branch_id')->where('branch_id', $request->select['id'])->take(500)->latest()->get();
						} else {
							return Shipment::whereNull('branch_id')->where('branch_id', $request->select['id'])->take(500)->latest()->get();
						}
					}
				} else {
					if ($request->selectAss['Assigned'] == 'All') {
						return Shipment::whereNotNull('branch_id')->where('branch_id', $request->select['id'])->where('status', $request->selectStatus['state'])->take(500)->latest()->get();
					} else {
						if ($request->selectAss['Assigned'] == 'Assigned') {
							return Shipment::whereNotNull('branch_id')->where('branch_id', $request->select['id'])->where('status', $request->selectStatus['state'])->take(500)->latest()->get();
						} else {
							return Shipment::whereNull('branch_id')->where('branch_id', $request->select['id'])->where('status', $request->selectStatus['state'])->take(500)->latest()->get();
						}
					}
				}
			}
		} else {
			if ($request->select['id'] == 'all') {
				if ($request->selectStatus['state'] == 'All') {
					if ($request->selectAss['Assigned'] == 'All') {
						return Shipment::whereNotNull('branch_id')->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->take(500)->latest()->get();
					} else {
						if ($request->selectAss['Assigned'] == 'Assigned') {
							return Shipment::whereNotNull('branch_id')->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->take(500)->latest()->get();
						} else {
							return Shipment::whereNull('branch_id')->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->take(500)->latest()->get();
						}
					}
				} else {
					if ($request->selectAss['Assigned'] == 'All') {
						return Shipment::whereNotNull('branch_id')->take(500)->where('status', $request->selectStatus['state'])->latest()->get();
					} else {
						if ($request->selectAss['Assigned'] == 'Assigned') {
							return Shipment::whereNotNull('branch_id')->where('status', $request->selectStatus['state'])->take(500)->latest()->get();
						} else {
							return Shipment::whereNull('branch_id')->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->take(500)->where('status', $request->selectStatus['state'])->latest()->get();
						}
					}
				}
			} else {

				if ($request->selectStatus['state'] == 'All') {
					if ($request->selectAss['Assigned'] == 'All') {
						return Shipment::whereNotNull('branch_id')->where('branch_id', $request->select['id'])->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->take(500)->latest()->get();
					// return Shipment::whereNotNull('branch_id')->latest()->take(500)->get();	
					} else {
						if ($request->selectAss['Assigned'] == 'Assigned') {
							return Shipment::whereNotNull('branch_id')->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('branch_id', $request->select['id'])->take(500)->latest()->get();
						} else {
							return Shipment::whereNull('branch_id')->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('branch_id', $request->select['id'])->take(500)->latest()->get();
						}
					}
				} else {
					if ($request->selectAss['Assigned'] == 'All') {
						return Shipment::whereNotNull('branch_id')->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->take(500)->where('status', $request->selectStatus['state'])->where('branch_id', $request->select['id'])->latest()->get();
					} else {
						if ($request->selectAss['Assigned'] == 'Assigned') {
							return Shipment::whereNotNull('branch_id')->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->where('status', $request->selectStatus['state'])->take(500)->where('branch_id', $request->select['id'])->latest()->get();
						} else {
							return Shipment::whereNull('branch_id')->whereBetween('created_at', [$request->form['start_date'], $request->form['end_date']])->take(500)->where('status', $request->selectStatus['state'])->where('branch_id', $request->select['id'])->latest()->get();
						}
					}
				}
			}
		}
	}

}
