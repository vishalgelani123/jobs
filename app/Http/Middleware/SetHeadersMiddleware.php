<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SetHeadersMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $host = $request->getSchemeAndHttpHost();

        //default-src 'self'; img-src 'self' https:; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline';

        /*Not Working headers for datatable*/
        //default-src 'self'; script-src 'self' " . $host . "; style-src 'self' " . $host . "; img-src 'self' data:; font-src 'self' " . $host . "; object-src 'none'; frame-src 'none';

        $response->headers->set('Content-Security-Policy', "default-src 'self'; img-src 'self' https:; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline';");
        if (!$response->headers->has('Content-Security-Policy')) {
            Log::info('Content-Security-Policy is not set');
        }

        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        if (!$response->headers->has('Strict-Transport-Security')) {
            Log::info('Strict-Transport-Security is not set');
        }

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        if (!$response->headers->has('X-Content-Type-Options')) {
            Log::info('X-Content-Type-Options is not set');
        }

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        if (!$response->headers->has('X-Frame-Options')) {
            Log::info('X-Frame-Options is not set');
        }

        return $response;
    }
}
