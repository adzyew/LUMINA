<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (app()->environment('local')) {
            $csp = "default-src * 'unsafe-inline' 'unsafe-eval' data: blob: http: https: ws: wss:;";
        } else {
            $appOrigin = '';
            $appUrl = (string) config('app.url', '');
            if ($appUrl !== '') {
                $scheme = parse_url($appUrl, PHP_URL_SCHEME);
                $host = parse_url($appUrl, PHP_URL_HOST);
                $isLoopbackHost = in_array($host, ['localhost', '127.0.0.1', '::1'], true);
                if ($scheme && $host && ! $isLoopbackHost) {
                    $appOrigin = "{$scheme}://{$host}";
                }
            }

            $formAction = "form-action 'self'";
            if ($appOrigin !== '') {
                $formAction .= " {$appOrigin}";
            }
            $formAction .= '; ';

            $csp = "default-src 'self'; "
                . "base-uri 'self'; "
                . "frame-ancestors 'self'; "
                . $formAction
                . "img-src 'self' data: https:; "
                . "font-src 'self' data: https:; "
                . "style-src 'self' 'unsafe-inline' https:; "
                . "script-src 'self' 'unsafe-inline' https:; "
                . "connect-src 'self' https:; "
                . "object-src 'none'; "
                . "upgrade-insecure-requests";
        }

        $response->headers->set('Content-Security-Policy', $csp);
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        return $response;
    }
}
