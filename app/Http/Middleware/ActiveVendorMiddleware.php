<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\Vendor;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ActiveVendorMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = User::where('id', Auth::id())->first();
        $vendor = Vendor::where('user_id', Auth::id())->first();

        if ($vendor && $vendor->status == 'pending') {
            return redirect()->route('pending.vendor');
        }

        return $next($request);
    }
}
