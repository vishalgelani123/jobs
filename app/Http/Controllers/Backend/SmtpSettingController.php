<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\MailSettingHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SmtpSetting\SmtpSettingStoreRequest;
use App\Mail\TestMail;
use App\Models\SmtpSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class SmtpSettingController extends Controller
{
    public function __construct()
    {
        MailSettingHelper::mailSetting();
    }
    public function index()
    {
        $smtpSetting = SmtpSetting::first();
        return view('backend.smtp-setting.index', compact('smtpSetting'));
    }

    public function store(SmtpSettingStoreRequest $request)
    {
        try {
            $smtpSetting = SmtpSetting::first();
            if (empty($smtpSetting)) {
                $smtpSetting = new SmtpSetting;
            }
            $smtpSetting->user_id = Auth::id();
            $smtpSetting->mail_from_name = $request->mail_from_name;
            $smtpSetting->mail_from_address = $request->mail_from_email;
            $smtpSetting->mail_username = $request->mail_username;
            $smtpSetting->mail_password = $request->mail_password;
            $smtpSetting->mail_port = $request->mail_port;
            $smtpSetting->mail_host = $request->mail_host;
            $smtpSetting->mail_encryption = $request->mail_encryption;
            $smtpSetting->save();
            return redirect()->back()->with(['success' => 'SMTP setting update successfully']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function testMail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        try {
            Mail::to($request->email)->send(new TestMail("Test Email - " . config('app.name')));

            return redirect()->back()->with(['success' => 'Test mail send successfully']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
}
