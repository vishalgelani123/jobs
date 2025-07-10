<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VendorApproveMail extends Mailable
{
    use Queueable, SerializesModels;

    public $vendor;

    public function __construct($vendor)
    {
        $this->vendor = $vendor;
    }

    public function build()
    {
        return $this->markdown('backend.emails.vendor-approve')
            ->subject(config('app.name'));
    }
}
