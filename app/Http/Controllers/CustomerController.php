<?php

namespace App\Http\Controllers;
use App\Shipment;
use App\Customer;
use Illuminate\Http\Request;
use Auth;
class CustomerController extends Controller
{
    public function customerShip()
    {
        return Shipment::where('client_id', 2)->paginate(10);
    }

    public function getsearchRe(Request $request)
    {
        // return $request->all();
        return Shipment::where('bar_code', $request->all())->paginate(10);
    }
}
