<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\Shipment;
use Auth;
class NotificationController extends Controller
{
    public function notifications()
    {
        $user = Auth::user();
        // return $user->notifications;
        $notification_data = [];
        foreach ($user->unreadNotifications as $notification) {
            // return $notification;
            // if ($notification->data['shipment']) {
                $notification_data[] = $notification->data['shipment'];
            // }
        }
        return $notification_data;
    }

    public function Notyshpments(Request $request, $id)
    {
        return Shipment::where('id', $id)->get();
    }

    public function read()
    {
        $user = Auth::user();
        foreach ($user->unreadNotifications as $notification) {
            $notification->markAsRead();
        }
        // $notification_data = [];
        // foreach ($user->unreadNotifications as $notification) {
        //     $notification_data[] = $notification->data['shipment'];
        // }
        // return $notification_data;
    }
}
