<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckOtpVerified
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->hasRole('vendor') && Auth::user()->is_otp_verified == 0) {
            return redirect()->route('otp.verify')->with(['success' => 'OTP sent to your mobile and email successfully']);
        }

        return $next($request);
    }
}
