<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DrafterApprovalMail extends Mailable
{
    use Queueable, SerializesModels;

    public $message;
    public $inquiry;
    public $user;

    public function __construct($message, $inquiry, $user)
    {
        $this->message = $message;
        $this->inquiry = $inquiry;
        $this->user = $user;
    }

    public function build()
    {
        return $this->markdown('backend.emails.drafter-approval')
            ->subject('Welcome to ' . config('app.name') . ' Procurement & Contracting!');
    }
}
