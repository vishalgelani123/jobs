<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FinalizeMail extends Mailable
{
    public $message;
    public $inquiry;
    public $vendor;

    use Queueable, SerializesModels;

    public function __construct($message, $inquiry, $vendor)
    {
        $this->message = $message;
        $this->inquiry = $inquiry;
        $this->vendor = $vendor;
    }

    public function build()
    {
        return $this->markdown('backend.emails.finalize-products')
            ->subject('Welcome to ' . config('app.name') . ' Procurement & Contracting!');
    }
}
