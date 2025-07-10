<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckVendorMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user->is_admin_created == '1' && $user->is_admin_password_reset == '0') {
            return redirect()->route('reset.password');
        }

        return $next($request);
    }
}
