<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\MailSettingHelper;
use App\Helpers\OTPHelper;
use App\Http\Controllers\Controller;
use App\Mail\LoginOtpMail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class VendorOTPVerifyController extends Controller
{
    public function __construct()
    {
        MailSettingHelper::mailSetting();
    }

    public function otpVerify()
    {
        return view('auth.otp-verify');
    }

    public function otpVerification(Request $request)
    {
        $otp = $request->otp_1 . $request->otp_2 . $request->otp_3 . $request->otp_4;
        if (!is_numeric($otp) || strlen($otp) != 4) {
            return redirect()->route('otp.verify')->with(['error' => 'OTP must be a 4-digit number.']);
        }
        $user = Auth::user();

        if ($otp == $user->otp) {
            $user->is_otp_verified = 1;
            $user->save();
            return redirect()->route('vendor-dashboard');
        }
        return redirect()->route('otp.verify')->with(['error' => 'OTP is incorrect']);
    }

    public function otpResend()
    {
        try {
            $user = Auth::user();

            if ($user->last_otp_send_date_time != "" && Carbon::parse($user->last_otp_send_date_time)->diffInSeconds(Carbon::now()) < 60) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Please wait 60 seconds before requesting a new OTP.'
                ]);
            }

            $otp = OTPHelper::generateOTP();

            if (config('services.whatsapp.api_url') != '') {
                Http::withOptions(['verify' => false])
                    ->post(config('services.whatsapp.api_url'), [
                        'campaing_id' => config('services.whatsapp.campaing_id'),
                        'token'       => config('services.whatsapp.token'),
                        'phone'       => "91" . $user->mobile,
                        'data'        => [
                            'name'    => $user->name,
                            'quote'   => "OTP : *" . $otp . "* for login",
                            'company' => config('app.name'),
                        ],
                    ]);
            }

            Mail::to($user->email)->send(new LoginOtpMail($otp, $user));

            $user->otp = $otp;
            $user->is_otp_verified = 0;
            $user->last_otp_send_date_time = Carbon::now();
            $user->save();

            return response()->json([
                'status'  => true,
                'message' => "OTP sent to your mobile and email successfully"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => "Something went wrong. please try again later."
            ]);
        }
    }
}
