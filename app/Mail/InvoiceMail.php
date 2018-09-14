<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Invoice;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice, $email_to, $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Invoice $invoice, $email_to, $subject)
    {
        $this->invoice = $invoice;
        $this->email_to = $email_to;
        $this->subject = $subject;

    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->markdown('emails/InvoiceMail');
        return $this->from('millers@millers.com')
                    // ->view('emails.InvoiceMail')
                    ->markdown('emails/InvoiceMail')
                    ->with([
                        'subject' => $this->subject
                    ])
                    ->to($this->email_to)
                    ->subject( $this->subject );
    }
}

