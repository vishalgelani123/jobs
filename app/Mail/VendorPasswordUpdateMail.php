<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VendorPasswordUpdateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $message;
    public $user;
    public $plainPassword;

    public function __construct($message, $user, $plainPassword)
    {
        $this->message = $message;
        $this->user = $user;
        $this->plainPassword = $plainPassword;
    }

    public function build()
    {
        return $this->markdown('backend.emails.vendor-password-update')
            ->subject('Password Update to ' . config('app.name') . ' Procurement & Contracting!');
    }
}
