<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ContentSecurityPolicy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (method_exists($response, 'header')) {
            $viteHost = app()->environment('local') ? " http://127.0.0.1:* http://localhost:* ws://127.0.0.1:* ws://localhost:*" : "";
            $response->header('Content-Security-Policy', "default-src 'self'; form-action 'self'; base-uri 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'{$viteHost}; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net{$viteHost}; font-src 'self' data: https://fonts.gstatic.com https://cdn.jsdelivr.net; img-src 'self' data: blob: https: http: https://ui-avatars.com; connect-src 'self' https://cdn.jsdelivr.net{$viteHost}; frame-src 'self' https://www.facebook.com https://www.youtube.com https://platform.twitter.com; object-src 'none'; frame-ancestors 'self';");
            $response->header('X-Frame-Options', 'SAMEORIGIN');
            $response->header('X-Content-Type-Options', 'nosniff');
            $response->header('X-XSS-Protection', '1; mode=block');
            $response->header('Referrer-Policy', 'strict-origin-when-cross-origin');
            $response->header('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
            
            // Remove headers that expose technology stack
            $response->headers->remove('X-Powered-By');
            $response->headers->remove('Server');
        }

        if (function_exists('header_remove') && !headers_sent()) {
            header_remove('X-Powered-By');
            header_remove('Server');
        }

        return $response;
    }
}
