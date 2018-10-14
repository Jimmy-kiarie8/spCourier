<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\User;
use App\Shipment;
class PermissionController extends Controller
{
    public function getPermissions()
    {
        return Permission::all();
    }



    public function getUsersCount() {
            return User::count();
    }

    public function getShipmentsCount() {
        return Shipment::count();
    }
    
    public function getCanceledCount() {
        return Shipment::where('status', 'Cancelled')->count();
    }
    
// Dashboard
    public function delayedShipmentCount() {
        return Shipment::where('status', 'Delayed')->count();
    }
    
    public function scheduledShipmentCount() {
        return Shipment::where('status', 'Scheduled')->count();
    }
    
    public function deriveredShipmentCount() {
        return Shipment::where('status', 'Derivered')->count();
    }
    

    public function dispatchedShipmentCount() {
        return Shipment::where('status', 'Dispatched')->count();
    }
} 