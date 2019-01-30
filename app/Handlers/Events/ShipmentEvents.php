<?php namespace App\Handlers\Events;

use App\Shipment;
use App\ScheduleLogs;
use App\Call;
// use App\Services\Email\Mailer; // This one I use to email as a service class
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\User;
class ShipmentEvents {

    protected $user = null;

    public function __construct(Shipment $shipment)
    {
        // $this->user = Auth::user();
    }

    public function shipmentCreated(Shipment $shipment)
    {
        // dd($shipment['id']);
        // new Call;
        $call = new Call;
        $call->event = 'created';
        $call->user_id = Auth::id();
        $call->shipment_id = $shipment->id;
        $call->shipment = serialize($shipment);
        $call->save();
        // dd($call);
        // Implement mailer or use laravel mailer directly
        // $this->mailer->notifyArticleCreated($article);
    }
    
    public function shipmentUpdated(Shipment $shipment)
    {
        // dd($shipment['id']);
        // $today = Carbon::today();
        // $tomorrow = Carbon::tomorrow();
        $shipments = Shipment::setEagerLoads([])->select('id')->get();
        // $shipments = Shipment::setEagerLoads([])->select('id')->whereBetween('created_at', [$today, $tomorrow])->get();
        // // $sh_count = Shipment::whereBetween('created_at', [$today, tomorrow])->count();
        $id = [];
        
        // // return array_flatten($shipments);
        
        foreach ($shipments as $shipment_id) {
            $id[] = $shipment_id->id;
        }
        // if (in_array($shipment->id, $id) && $shipment['status'] == 'Scheduled') {
            $users = 1;
            // dd($user);
            // ScheduleLogs::where('user_id', $users)->increment('count');
            
            // Retrieve by name, or instantiate with the name and delayed attributes...
            $logs = ScheduleLogs::firstOrNew(
                ['user_id' => $users]
            );
            // dd($logs);
            // if (!empty($logs)) {
            //     ScheduleLogs::increment('count');
            // }else {
                // $schedule = new ScheduleLogs;
                // $logs->user_id = Auth::id();
                // $call->shipment = serialize($shipment);
                $logs->count = 1;
                $logs->save();
            // }
        // }


        // dd($shipment['id']);
        // dd($shipment);
        // new Call;
        // $call = new Call;
        // $call->user_id = Auth::id();
        // $call->event = 'updated';
        // $call->shipment_id = $shipment['id'];
        // $call->shipment = serialize($shipment);
        // $call->save();
        // Implement mailer or use laravel mailer directly
        // $this->mailer->notifyArticleCreated($article);
    }
    
    public function shipmentDeleted(Shipment $shipment)
    {
        // dd($shipment['id']);
        // dd($shipment);
        // new Call;
        $call = new Call;
        $call->event = 'deleted';
        $call->user_id = Auth::id();
        $call->shipment_id = $shipment['id'];
        $call->shipment = serialize($shipment);
        $call->save();
        // Implement mailer or use laravel mailer directly
        // $this->mailer->notifyArticleCreated($article);
    }
    

    // Other Handlers/Methods...
}