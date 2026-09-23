# Google AdSense and Search Console setup

The codebase is wired for AdSense but contains **no publisher ID**, because one
must come from your own AdSense account. Nothing here invents an ID, and no
placeholder is ever rendered.

## What the code does today

| Condition | Behaviour |
|---|---|
| `ADSENSE_CLIENT_ID` empty | No AdSense script. No ad containers. `/ads.txt` returns 404. Site works normally. |
| `ADSENSE_CLIENT_ID` set to a valid `ca-pub-…` | Loader script added to `<head>`. Ad slots render. `/ads.txt` serves the correct line automatically. |

The value is validated against `^ca-pub-\d{10,20}$`, so a typo or a leftover
placeholder will not switch advertising on.

### Where ads can appear

Slots are included through `partials/ads/slot.blade.php` at:

- the homepage, between the popular tools and the categories
- each tool page, between the "how to use" and "features" blocks
- each category page, below the tool grid

They are **not** placed on the privacy, cookie, terms, disclaimer, contact or
error pages, and never inside a tool's controls, so an ad cannot be mistaken
for a button or catch a mis-tap.

## Your manual steps, in order

1. **Buy and configure a domain**, and point it at the server.
2. **Deploy the site** following `docs/DEPLOYMENT.md`.
3. **Enable HTTPS** with a valid certificate and an HTTP → HTTPS redirect.
4. **Confirm the site is publicly reachable** — Google must be able to fetch it.
   Check `https://yourdomain.com/robots.txt` shows your domain in the Sitemap
   line; if it does not, `SITE_URL` is wrong.
5. **Create or sign in to AdSense** at <https://adsense.google.com>.
6. **Add your site** in AdSense → Sites → Add site.
7. **Verify ownership** using whichever method AdSense offers you. It will
   normally ask you to place its snippet in `<head>`:
   - Set `ADSENSE_CLIENT_ID=ca-pub-…` in `.env`
   - Run `php artisan config:cache`
   - The loader appears in `<head>` on every page, which satisfies the
     code-snippet verification method.
8. **Configure Privacy & Messaging** in AdSense → Privacy & messaging. If you
   have any traffic from the EEA, the UK or Switzerland you must enable a
   Google-certified CMP integrated with the IAB TCF. Google provides one in the
   AdSense UI; turn on the GDPR message and publish it.
   *This is configured in the AdSense account, not in this codebase.* No
   home-grown consent banner is shipped here, because a banner that does not
   actually transmit TCF consent signals would be worse than none.
   Enable the CCPA/US states message too if you expect US traffic.
9. **Verify `/ads.txt`.** Once `ADSENSE_CLIENT_ID` is set, visiting
   `https://yourdomain.com/ads.txt` returns:
   ```
   google.com, pub-XXXXXXXXXXXXXXXX, DIRECT, f08c47fec0942fa0
   ```
   Compare it character for character with the line AdSense shows you under
   Sites → ads.txt. If AdSense gives you a different line, use theirs.
10. **Request review** in AdSense and wait. Reviews commonly take days rather
    than hours.
11. **After approval**, turn on Auto ads or create manual units. For manual
    units, pass the slot ID:
    ```blade
    @include('partials.ads.slot', ['position' => 'in-content', 'slotId' => '1234567890'])
    ```

## Google Search Console

1. Add the property at <https://search.google.com/search-console> — use the
   Domain property type if you can add a DNS TXT record, otherwise URL prefix
   with the exact `SITE_URL` value.
2. Verify ownership via DNS, or the HTML tag method (add the tag using the
   `@push('meta')` stack in any layout-extending view).
3. Submit `https://yourdomain.com/sitemap.xml`.
4. Use URL Inspection on the homepage and two or three tool pages to confirm
   Google sees them as indexable.
5. Check Settings → Crawl stats after a few days for fetch errors.

## An honest note on approval

Everything technically within our control has been implemented: original
explanatory content on every tool page, working navigation, the legal and trust
pages, canonical URLs, a sitemap, robots.txt, mobile responsiveness, no broken
routes and no exposed errors. Approval is still Google's decision and depends
on factors such as traffic history and content depth that no codebase can
guarantee. Do not treat this checklist as a promise of approval.
