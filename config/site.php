<?php

/*
|--------------------------------------------------------------------------
| Site configuration
|--------------------------------------------------------------------------
|
| Branding, contact details and advertising configuration. Everything here is
| driven by environment variables so nothing environment-specific or secret is
| committed to the repository.
|
*/

return [

    'name' => env('SITE_NAME', 'AiToolyfy'),

    'tagline' => env('SITE_TAGLINE', 'Free online tools for documents, text and everyday tasks'),

    'description' => env(
        'SITE_DESCRIPTION',
        'A free collection of browser-based tools for PDFs, text, developers and everyday tasks. '
        . 'Most tools run entirely in your browser, so your files never leave your device.'
    ),

    /*
    | Public contact address. Set CONTACT_EMAIL in .env for production; the
    | contact page degrades gracefully when it is not configured.
    */
    'contact_email' => env('CONTACT_EMAIL'),

    /*
    | Used for canonical URLs, the sitemap and Open Graph tags. Falls back to
    | APP_URL. In production this must be the https:// production domain.
    */
    'url' => rtrim(env('SITE_URL', env('APP_URL', 'http://localhost')), '/'),

    'locale' => env('SITE_LOCALE', 'en'),

    'theme_color' => '#ff7a18',

    /*
    |----------------------------------------------------------------------
    | Google AdSense
    |----------------------------------------------------------------------
    |
    | ADSENSE_CLIENT_ID must be the exact publisher ID issued by AdSense, in
    | the form ca-pub-XXXXXXXXXXXXXXXX. While it is empty:
    |
    |   - no AdSense script is emitted
    |   - no ad containers are rendered
    |   - /ads.txt returns 404 rather than inventing a publisher line
    |
    | The site works normally either way. Never commit a real ID here; set it
    | in the production .env only.
    |
    */
    'adsense' => [
        'client_id' => env('ADSENSE_CLIENT_ID'),

        // Auto ads let Google place units itself. Manual slots can be added
        // later through the ad partials once units exist in the account.
        'auto_ads' => env('ADSENSE_AUTO_ADS', true),
    ],

];
