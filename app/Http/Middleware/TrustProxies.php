<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies;

    public function __construct()
    {
        /*
         * Set TRUSTED_PROXIES in the deployment environment when the app runs
         * behind a load balancer or reverse proxy that terminates TLS - use
         * "*" for a container behind an ALB/ingress, or a comma-separated
         * list of addresses. Without this, X-Forwarded-Proto is ignored and
         * Laravel cannot tell that the original request was HTTPS.
         */
        $proxies = env('TRUSTED_PROXIES');

        if (is_string($proxies) && $proxies !== '') {
            $this->proxies = $proxies === '*' ? '*' : explode(',', $proxies);
        }
    }

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;
}
