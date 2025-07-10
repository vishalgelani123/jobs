<?php

namespace App\Console\Commands;

use App\Helpers\MailSettingHelper;
use App\Mail\SendEmail;
use App\Models\SendMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendMails extends Command
{
    protected $signature = 'send-mails';

    protected $description = '';

    public function __construct()
    {
        parent::__construct();
        MailSettingHelper::mailSetting();
    }

    public function handle()
    {
        try {
            $sendMails = SendMail::all();
            foreach ($sendMails as $sendMail) {
                Mail::to($sendMail->email)->send(new SendEmail($sendMail));
                SendMail::where('id', $sendMail->id)->delete();
            }
        } catch (\Exception $e) {
            Log::info("Sending mail error : " . $e->getMessage());
        }
    }
}
