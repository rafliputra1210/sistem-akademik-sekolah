<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request and append critical HTTP security headers.
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        // Mencegah Clickjacking dengan membatasi rendering iframe hanya pada domain sendiri
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Mencegah MIME sniffing exploit
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Mengatur kontrol referrer agar data URL sensitif tidak bocor ke pihak ketiga
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Mengizinkan kamera hanya untuk domain sendiri (dibutuhkan untuk absensi selfie guru)
        $response->headers->set('Permissions-Policy', 'camera=(self), microphone=(), geolocation=()');

        return $response;
    }
}
