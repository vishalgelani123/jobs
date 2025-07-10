<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PreVendorDetailMail extends Mailable
{
    use Queueable, SerializesModels;

    public $preVendorDetail;

    public function __construct($preVendorDetail)
    {
        $this->preVendorDetail = $preVendorDetail;
    }

    public function build()
    {
        return $this->markdown('backend.emails.pre-vendor-detail')
            ->subject('Welcome to ' . config('app.name') . ' Procurement & Contracting!');
    }
}
