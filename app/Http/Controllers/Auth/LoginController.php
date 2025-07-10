<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\MailSettingHelper;
use App\Helpers\OTPHelper;
use App\Http\Controllers\Controller;
use App\Mail\LoginOtpMail;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        MailSettingHelper::mailSetting();
    }


}
