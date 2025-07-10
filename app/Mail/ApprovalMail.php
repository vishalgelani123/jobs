<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApprovalMail extends Mailable
{
    use Queueable, SerializesModels;

    public $inquiryApproval;
    public $inquiry;

    public function __construct($inquiryApproval,$inquiry)
    {
        $this->inquiryApproval = $inquiryApproval;
        $this->inquiry = $inquiry;
    }

    public function build()
    {
        return $this->markdown('backend.emails.inquiry-approval')
            ->subject('Welcome to ' . config('app.name') . ' Procurement & Contracting!');
    }
}
