<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VendorRegistrationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;

    public function __construct($user,$password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function build()
    {
        return $this->markdown('backend.emails.vendor-registration-mail')
            ->subject('Registration Email - ' . config('app.name') . ' Procurement & Contracting!');
    }
}
