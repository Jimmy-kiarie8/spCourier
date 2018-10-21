<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\Shipment;

class CanceledCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Canceled:CanceledShipments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancele shipments if not delivered within 30 days';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
		$today = Carbon::today();
		// $prev_month = $today->subMonth();
        // $nxt_month = $today->addMonth();
        dd(Shipment::whereBetween('created_at', [$today->subMonth(), $today->addMonth()])->where('status', '!=', 'Scheduled')->orWhere('status', '!=', 'Delivered')->get());
        // dd($prev_month);
    }
}
