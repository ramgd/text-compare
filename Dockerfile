FROM php:8.2-apache

# ---------------------------------------------------------------------------
# PHP extensions
#
# The base php:8.2-apache image already ships ctype, dom, fileinfo, iconv,
# json, libxml, mbstring, openssl, pcre, tokenizer and xml, which covers most
# of what composer.lock asks for. Only three are genuinely missing:
#
#   zip        required by phpoffice/phpword (reads .docx Open XML packages)
#   gd         required by phpoffice/phpword and simplesoftwareio/simple-qrcode
#   pdo_mysql  the configured database driver
#
# libzip-dev is only the system library; "docker-php-ext-install zip" is what
# actually builds the PHP extension. Having the system package without that
# step is what produced "phpoffice/phpword 1.4.0 requires ext-zip -> it is
# missing from your system".
#
# libpng-dev is needed for GD to support PNG, which the QR generator uses.
# ---------------------------------------------------------------------------
# Build the extensions, then drop the -dev headers that were only needed to
# compile them. The runtime shared libraries the compiled .so files link
# against are marked manual first so --auto-remove cannot take them:
# libzip5 in particular provides libzip.so.5, without which zip.so fails to
# load at startup. No "#" comments inside the continued shell line below - a
# comment there would silently swallow the rest of the command.
RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        libzip-dev \
        libjpeg-dev \
        libpng-dev \
        libfreetype6-dev \
        zip \
        unzip \
        git \
        curl; \
    docker-php-ext-configure gd --with-freetype --with-jpeg; \
    docker-php-ext-install -j"$(nproc)" gd zip pdo pdo_mysql; \
    apt-mark manual libzip5 libjpeg62-turbo libpng16-16 libfreetype6; \
    apt-get purge -y --auto-remove \
        libzip-dev libjpeg-dev libpng-dev libfreetype6-dev; \
    docker-php-source delete; \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Fail the build here rather than at runtime if an extension did not build.
RUN set -eux; \
    php -m 2>&1 | grep -qi 'Unable to load' && { echo 'an extension failed to load'; php -m; exit 1; } || true; \
    php -m | grep -qx 'zip'; \
    php -m | grep -qx 'gd'; \
    php -m | grep -qx 'pdo_mysql'; \
    php -r 'new ZipArchive(); if (!function_exists("imagecreatetruecolor")) { exit(1); }'; \
    echo 'verified: zip, gd, pdo_mysql load correctly'

# ---------------------------------------------------------------------------
# PHP runtime configuration
# ---------------------------------------------------------------------------
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Word to PDF accepts .docx up to 20 MB; allow headroom for the multipart body.
RUN { \
        echo 'upload_max_filesize = 25M'; \
        echo 'post_max_size = 30M'; \
        echo 'memory_limit = 256M'; \
        echo 'max_execution_time = 120'; \
        echo 'expose_php = Off'; \
    } > "$PHP_INI_DIR/conf.d/zz-app.ini"

# Opcache: code is immutable in the image, so timestamps never need revalidating.
RUN { \
        echo 'opcache.enable=1'; \
        echo 'opcache.memory_consumption=128'; \
        echo 'opcache.max_accelerated_files=20000'; \
        echo 'opcache.validate_timestamps=0'; \
    } > "$PHP_INI_DIR/conf.d/zz-opcache.ini"

# ---------------------------------------------------------------------------
# Apache
# ---------------------------------------------------------------------------
RUN a2enmod rewrite headers

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
        /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
        /etc/apache2/apache2.conf \
        /etc/apache2/conf-available/*.conf

# The base image ships "AllowOverride None" for /var/www, so Apache ignores
# public/.htaccess and every Laravel route except "/" returns 404. Allow the
# override inside the document root, and deny everything above it.
RUN printf '%s\n' \
        '<Directory /var/www/html/public>' \
        '    Options -Indexes +FollowSymLinks' \
        '    AllowOverride All' \
        '    Require all granted' \
        '</Directory>' \
        '' \
        '<Directory /var/www/html>' \
        '    Options -Indexes' \
        '    AllowOverride None' \
        '    Require all denied' \
        '</Directory>' \
        '' \
        'ServerTokens Prod' \
        'ServerSignature Off' \
        'TraceEnable Off' \
        > /etc/apache2/conf-available/z-laravel.conf \
    && a2enconf z-laravel

WORKDIR /var/www/html

# ---------------------------------------------------------------------------
# Dependencies
#
# Manifests are copied first so this layer is reused when application code
# changes but dependencies do not.
# ---------------------------------------------------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./

# --no-scripts here only because post-autoload-dump boots Laravel through
# artisan, and the application code has not been copied yet. It is run below.
RUN composer install \
        --no-dev \
        --optimize-autoloader \
        --no-interaction \
        --prefer-dist \
        --no-scripts \
        --no-progress

# ---------------------------------------------------------------------------
# Application
#
# .env is excluded by .dockerignore: configuration arrives at runtime through
# environment variables, so no secret is ever baked into the image.
# ---------------------------------------------------------------------------
COPY . .

RUN composer dump-autoload --no-dev --optimize --no-interaction \
    && php artisan package:discover --ansi

# Prove the platform requirements are genuinely met. No --ignore-platform-req
# is used anywhere in this build.
RUN composer check-platform-reqs --no-dev

# ---------------------------------------------------------------------------
# Writable paths and ownership
# ---------------------------------------------------------------------------
RUN mkdir -p \
        storage/app/doc-convert \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && find /var/www/html -maxdepth 1 -name '.env*' ! -name '.env.example' -delete

EXPOSE 80

# Config and route caches are deliberately not baked in: that would freeze
# build-time environment values into the image. The entrypoint builds them at
# container start, once the real environment is present.
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

HEALTHCHECK --interval=30s --timeout=5s --start-period=20s --retries=3 \
    CMD curl -fsS http://127.0.0.1/ >/dev/null || exit 1

ENTRYPOINT ["entrypoint.sh"]
CMD ["apache2-foreground"]
