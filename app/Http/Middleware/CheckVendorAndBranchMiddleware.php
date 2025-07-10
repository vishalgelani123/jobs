<?php

namespace App\Http\Middleware;

use App\Models\Branch;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckVendorAndBranchMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $vendor = $request->route('vendor');
        $branch = $request->route('branch');

        $findBranchDetail = Branch::where('id', $branch->id)->where('vendor_id', $vendor->id)->first();

        if (empty($findBranchDetail)) {
            abort(404);
        }

        return $next($request);
    }
}
