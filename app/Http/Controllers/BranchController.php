<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Http\Requests\BranchesRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller {
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		// return $request->all();
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
	public function update(Request $request, Branch $branch) {
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
	public function destroy(Branch $branch) {
		//
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getBranch() {
		return Branch::All();
	}
	public function getBranchShip(Request $request, $id) {
		return Branch::where('id', $id)->get();
	}

}
