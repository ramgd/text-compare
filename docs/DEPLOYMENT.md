# Production deployment

Everything in this document is a manual step. The application is ready for all
of them; none of them can be done from the codebase.

## 1. Server requirements

- PHP 8.1+ with `zip`, `dom`, `xml`, `mbstring`, `gd` and `fileinfo`
  (all are required by the Word to PDF converter)
- A web server whose document root is `public/`, never the project root
- Composer

## 2. Deploy

```bash
git clone <your-repo> /var/www/aitoolyfy
cd /var/www/aitoolyfy

composer install --no-dev --optimize-autoloader
npm ci && npm run prod

cp .env.example .env
php artisan key:generate
```

## 3. Configure `.env` for production

These are the values that must change from their defaults:

```ini
APP_ENV=production
APP_DEBUG=false                      # leaving this true exposes stack traces
APP_URL=https://yourdomain.com

SITE_URL=https://yourdomain.com      # canonical tags, sitemap, robots.txt
SITE_NAME="Your Brand"
CONTACT_EMAIL=hello@yourdomain.com   # shown on /contact and in the footer

SESSION_SECURE_COOKIE=true           # HTTPS only
SESSION_SAME_SITE=lax

ADSENSE_CLIENT_ID=                   # leave empty until Google issues one
```

`SITE_URL` must have **no trailing slash** and must be the exact host you want
indexed (pick either `www.` or the apex, not both).

## 4. Cache for production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Re-run these after any future `.env` change, or the old values stay cached.

## 5. File permissions

```bash
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

`storage/app/doc-convert/` is created automatically for Word to PDF uploads and
each request's directory is removed straight after conversion. It sits under
`storage/`, outside the document root, so it is never web-accessible.

## 6. HTTPS

Obtain a certificate (Let's Encrypt is fine) and redirect HTTP to HTTPS at the
web server. Example for nginx:

```nginx
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    return 301 https://yourdomain.com$request_uri;
}

server {
    listen 443 ssl http2;
    server_name yourdomain.com;

    root /var/www/aitoolyfy/public;
    index index.php;

    ssl_certificate     /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;

    # Word to PDF accepts .docx up to 20 MB; allow a little headroom.
    client_max_body_size 25M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
    }

    # Never serve dotfiles.
    location ~ /\.(?!well-known) { deny all; }

    # Long cache for fingerprinted assets.
    location ~* \.(css|js|woff2?|png|jpg|svg|ico)$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
    }

    gzip on;
    gzip_types text/css application/javascript application/json image/svg+xml text/plain application/xml;
}
```

Also redirect `www` to the apex (or the reverse) so only one host is indexed,
and make sure that choice matches `SITE_URL`.

## 7. Verify before announcing

```bash
curl -I  https://yourdomain.com/                 # expect 200
curl -sI http://yourdomain.com/  | head -1       # expect 301 to https
curl -s  https://yourdomain.com/robots.txt       # Sitemap line must show your domain
curl -s  https://yourdomain.com/sitemap.xml | head
curl -sI https://yourdomain.com/nope | head -1   # expect 404, branded page
curl -s  https://yourdomain.com/.env             # must NOT return the file
```

Confirm that an error page shows the branded message and no stack trace. With
`APP_DEBUG=false` this is already the behaviour; it has been verified locally.
