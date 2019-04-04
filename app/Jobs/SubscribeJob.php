<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\CampaignReady;

class SubscribeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $subscriber, $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Subscriber $subscriber, $data=[])
    {
        $this->subscriber = $subscriber;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // $email = new ReportSent( $this->report, $this->attachments );
        // Mail::to( $this->report->user->email )
            // ->send( $email );
        $email = new CampaignReady($data);
        Mail::to($this->subscriber->email)->send($email)->now()->addSeconds(5);
    }
}
