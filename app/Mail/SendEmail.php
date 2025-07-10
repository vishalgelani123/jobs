<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $sendMail;

    public function __construct($sendMail)
    {
        $this->sendMail = $sendMail;
    }

    public function build()
    {
        return $this->markdown('backend.emails.send-mail')
            ->subject($this->sendMail->subject . ' ' . config('app.name') . ' Procurement & Contracting!');
    }
}
