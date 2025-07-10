<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminStatusApproveInquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    public $message;
    public $inquiry;
    public $vendor;

    public function __construct($message, $inquiry, $vendor)
    {
        $this->message = $message;
        $this->inquiry = $inquiry;
        $this->vendor = $vendor;
    }

    public function build()
    {
        return $this->markdown('backend.emails.admin-status-approve-inquiry')
            ->subject('Welcome to ' . config('app.name') . ' Procurement & Contracting!');
    }
}
