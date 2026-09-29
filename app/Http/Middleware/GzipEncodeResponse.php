<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GzipEncodeResponse
{
    /**
     * Handle an incoming request and compress response with gzip if supported by client.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Do not compress binary files or streamed responses
        if ($response instanceof BinaryFileResponse || $response instanceof StreamedResponse) {
            return $response;
        }

        // If response is already encoded or client does not support gzip, skip compression
        if ($response->headers->has('Content-Encoding')) {
            return $response;
        }

        $acceptEncoding = $request->header('Accept-Encoding', '');

        if (str_contains($acceptEncoding, 'gzip') && function_exists('gzencode')) {
            $content = $response->getContent();

            // Only compress if content length is greater than 1KB
            if (is_string($content) && strlen($content) > 1024) {
                $compressed = gzencode($content, 6);

                if ($compressed !== false) {
                    $response->setContent($compressed);
                    $response->headers->set('Content-Encoding', 'gzip');
                    $response->headers->set('Vary', 'Accept-Encoding', false);
                    $response->headers->set('Content-Length', (string) strlen($compressed));
                }
            }
        }

        return $response;
    }
}
