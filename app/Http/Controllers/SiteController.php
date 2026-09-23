<?php

namespace App\Http\Controllers;

use App\Support\ToolRegistry;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Public pages that are not tools: the homepage, the tool directory, the
 * category listings, the legal and trust pages, and the machine-readable
 * files search engines and AdSense look for.
 */
class SiteController extends Controller
{
    public function home()
    {
        return view('pages.home', [
            'featured'   => ToolRegistry::featured(),
            'categories' => ToolRegistry::categoriesWithTools(),
            'toolCount'  => ToolRegistry::count(),
        ]);
    }

    public function tools()
    {
        return view('pages.tools', [
            'categories' => ToolRegistry::categoriesWithTools(),
            'allTools'   => ToolRegistry::all(),
            'toolCount'  => ToolRegistry::count(),
        ]);
    }

    public function category(string $slug)
    {
        $category = ToolRegistry::category($slug);

        abort_if($category === null, 404);

        $tools = ToolRegistry::inCategory($slug);

        abort_if($tools->isEmpty(), 404);

        return view('pages.category', [
            'category' => $category,
            'tools'    => $tools,
        ]);
    }

    public function about()
    {
        return view('pages.about', [
            'categories' => ToolRegistry::categoriesWithTools(),
            'toolCount'  => ToolRegistry::count(),
        ]);
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    public function cookies()
    {
        return view('pages.cookies');
    }

    public function disclaimer()
    {
        return view('pages.disclaimer');
    }

    /**
     * XML sitemap.
     *
     * Only public, canonical, indexable URLs. No internal endpoints, no POST
     * targets, and nothing that would duplicate another URL.
     */
    public function sitemap(): Response
    {
        $base = rtrim(config('site.url'), '/');

        $urls = [
            ['loc' => $base . '/',                'priority' => '1.0', 'changefreq' => 'weekly'],
            ['loc' => $base . '/tools',           'priority' => '0.9', 'changefreq' => 'weekly'],
            ['loc' => $base . '/about',           'priority' => '0.5', 'changefreq' => 'yearly'],
            ['loc' => $base . '/contact',         'priority' => '0.5', 'changefreq' => 'yearly'],
            ['loc' => $base . '/privacy-policy',  'priority' => '0.3', 'changefreq' => 'yearly'],
            ['loc' => $base . '/terms-of-service','priority' => '0.3', 'changefreq' => 'yearly'],
            ['loc' => $base . '/cookie-policy',   'priority' => '0.3', 'changefreq' => 'yearly'],
            ['loc' => $base . '/disclaimer',      'priority' => '0.3', 'changefreq' => 'yearly'],
        ];

        foreach (ToolRegistry::categoriesWithTools() as $key => $category) {
            $urls[] = [
                'loc'        => $base . '/tools/' . $category['slug'],
                'priority'   => '0.7',
                'changefreq' => 'monthly',
            ];
        }

        foreach (ToolRegistry::all() as $tool) {
            $urls[] = [
                'loc'        => $base . $tool['path'],
                'priority'   => ! empty($tool['featured']) ? '0.9' : '0.8',
                'changefreq' => 'monthly',
            ];
        }

        $lastmod = now()->toAtomString();

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
            . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $url) {
            $xml .= "    <url>\n"
                . '        <loc>' . htmlspecialchars($url['loc'], ENT_XML1) . "</loc>\n"
                . '        <lastmod>' . $lastmod . "</lastmod>\n"
                . '        <changefreq>' . $url['changefreq'] . "</changefreq>\n"
                . '        <priority>' . $url['priority'] . "</priority>\n"
                . "    </url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    /**
     * robots.txt, generated so the sitemap URL always matches the configured
     * production host rather than a hard-coded domain.
     */
    public function robots(): Response
    {
        $base = rtrim(config('site.url'), '/');

        $lines = [
            'User-agent: *',
            'Allow: /',
            '',
            '# Internal endpoints - nothing here is a public page.',
            'Disallow: /pdf-toolkit/word-to-pdf',
            '',
            '# AdSense and Google crawlers need access to public content.',
            'User-agent: Mediapartners-Google',
            'Allow: /',
            '',
            'User-agent: AdsBot-Google',
            'Allow: /',
            '',
            'Sitemap: ' . $base . '/sitemap.xml',
            '',
        ];

        return response(implode("\n", $lines), 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    /**
     * ads.txt.
     *
     * Served only when a real publisher ID is configured. Until then this
     * returns 404, which is the correct state for a site with no publisher
     * relationship - an ads.txt containing a made-up ID would be worse than
     * none at all, and Google treats an invalid entry as a problem to fix.
     */
    public function adsTxt(): Response
    {
        $client = config('site.adsense.client_id');

        if (! is_string($client) || preg_match('/^ca-pub-(\d{10,20})$/', $client, $matches) !== 1) {
            abort(404);
        }

        // Google's required format: the publisher ID without the "ca-" prefix.
        $publisherId = 'pub-' . $matches[1];

        $body = "google.com, {$publisherId}, DIRECT, f08c47fec0942fa0\n";

        return response($body, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
