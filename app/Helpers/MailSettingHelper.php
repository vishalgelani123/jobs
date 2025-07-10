<?php

namespace App\Helpers;

use App\Models\SmtpSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;

class MailSettingHelper
{
    public static function mailSetting()
    {
        $domain = Request::getHost();
        $smtpSetting = SmtpSetting::first();

        if ($smtpSetting != null && $domain != "alembic-realestate.test") {
            config(['mail.from.address' => $smtpSetting->mail_from_address]);
            config(['mail.from.name' => config('app.name')]);

            Config::set('mail.mailers.smtp.host', $smtpSetting->mail_host);
            Config::set('mail.mailers.smtp.port', $smtpSetting->mail_port);
            Config::set('mail.mailers.smtp.username', $smtpSetting->mail_username);
            Config::set('mail.mailers.smtp.password', $smtpSetting->mail_password);
            Config::set('mail.mailers.smtp.encryption', $smtpSetting->mail_encryption);
            return Config::get('mail.mailers.smtp');
        }
    }
}
