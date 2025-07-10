<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminProductMail extends Mailable
{
    use Queueable, SerializesModels;

    public $message;
    public $inquiry;

    public function __construct($message, $inquiry)
    {
        $this->message = $message;
        $this->inquiry = $inquiry;
    }

    public function build()
    {
        return $this->markdown('backend.emails.admin-products')
            ->subject('Welcome to ' . config('app.name') . ' Procurement & Contracting!');
    }
}
