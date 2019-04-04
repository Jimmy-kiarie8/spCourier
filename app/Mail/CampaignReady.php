<?php

namespace App\Mail;

use App\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CampaignReady extends Mailable
{
    use Queueable, SerializesModels;

    public $subscriber, $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Subscriber $subscriber, $data)
    {
        $this->subscriber = $subscriber;
        $this->data = $data;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->from('jimlaravel@gmail.com')
            ->view('emails.campaign')
            ->with([
                'name' => $this->subscriber->name,
                'title' => $this->data['title'],
                'content' => $this->data['content'],
            ])
        // ->attach($this->data['filepath'], ['mime' => $this->data['mime']])
            ->subject($this->data['title']);
        foreach ($this->data['filepath'] as $file) {
            $email->attach(public_path() . '/storage/file/' . $file['file']); // attach each file
        }
        return $email;
    }
}
