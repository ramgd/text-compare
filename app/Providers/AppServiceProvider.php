<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap application services.
     */
    public function boot(): void
    {
        /*
         * Production is served over HTTPS, so URLs are generated with that
         * scheme. This stays the default in production - unchanged behaviour -
         * but APP_FORCE_HTTPS=false lets a staging or container deployment
         * that genuinely serves plain HTTP generate working asset URLs
         * instead of https:// links that cannot load.
         *
         * When the app sits behind a TLS-terminating proxy, prefer setting
         * TRUSTED_PROXIES so X-Forwarded-Proto is honoured and no forcing is
         * needed at all.
         */
        $forceHttps = env('APP_FORCE_HTTPS', $this->app->environment('production'));

        if (filter_var($forceHttps, FILTER_VALIDATE_BOOLEAN)) {
            URL::forceScheme('https');
        }
    }
}
