<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Baseline security response headers.
 *
 * Deliberately conservative: no Content-Security-Policy is set here, because
 * the tools legitimately load libraries from cdnjs/jsDelivr and AdSense
 * injects its own scripts and frames, and a CSP written without testing every
 * one of those would silently break tools in production. The headers below
 * are safe for this application as it stands.
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        // Never let a browser second-guess a declared content type. This also
        // matters for /ads.txt, /robots.txt and /sitemap.xml.
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Legacy clickjacking protection for browsers without frame-ancestors.
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Send the origin to other sites, the full URL within our own.
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // No tool needs the camera, microphone or geolocation.
        $response->headers->set(
            'Permissions-Policy',
            'camera=(), microphone=(), geolocation=(), interest-cohort=()'
        );

        // HSTS only over a real TLS connection, and only in production, so a
        // local http:// install is never pinned to https in a developer's
        // browser.
        if ($request->secure() && app()->environment('production')) {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains'
            );
        }

        return $response;
    }
}
