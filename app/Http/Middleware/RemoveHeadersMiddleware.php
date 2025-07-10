<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RemoveHeadersMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->remove('X-Cloud-Metadata');
        if ($response->headers->has('X-Cloud-Metadata')) {
            Log::info('X-Cloud-Metadata is not removed');
        }

        $response->headers->remove('X-Instance-ID');
        if ($response->headers->has('X-Instance-ID')) {
            Log::info('X-Instance-ID is not removed');
        }

        $response->headers->remove('X-Powered-By');
        if ($response->headers->has('X-Powered-By')) {
            Log::info('X-Powered-By is not removed');
        }

        $response->headers->remove('Server');
        header_remove("Server");
        if ($response->headers->has('Server')) {
            Log::info('Server is not removed');
        }

        return $response;
    }
}
