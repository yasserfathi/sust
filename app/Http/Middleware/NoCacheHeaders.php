<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NoCacheHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        return $response->header('Expires', 'Tue, 03 Jul 2001 06:00:00 GMT')
                        ->header('Last-Modified', gmdate('D, d M Y H:i:s') . ' GMT')
                        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                        ->header('Pragma', 'no-cache');
    }
}
