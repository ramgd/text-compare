#!/bin/sh
set -e

# ---------------------------------------------------------------------------
# Container entrypoint.
#
# Runs once per container start, before Apache. Caches are built here rather
# than at image build time so they reflect the real runtime environment
# instead of freezing build-time values into the image.
# ---------------------------------------------------------------------------

cd /var/www/html

# APP_KEY must come from the environment. Generating one per container would
# invalidate every existing session and encrypted value on each restart, so
# this warns instead of silently papering over a missing secret.
if [ -z "${APP_KEY:-}" ]; then
    echo "entrypoint: WARNING - APP_KEY is not set. Set it in the deployment environment." >&2
fi

# These directories are volume mount points in some deployments, so ensure
# they exist and are writable on every start, not only at build time.
mkdir -p \
    storage/app/doc-convert \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

# Clear anything stale, then cache against the current environment.
php artisan config:clear >/dev/null 2>&1 || true
php artisan route:clear  >/dev/null 2>&1 || true
php artisan view:clear   >/dev/null 2>&1 || true

if php artisan config:cache >/dev/null 2>&1; then
    echo "entrypoint: configuration cached"
else
    echo "entrypoint: WARNING - config:cache failed; continuing uncached" >&2
fi

php artisan route:cache >/dev/null 2>&1 || echo "entrypoint: route:cache skipped" >&2
php artisan view:cache  >/dev/null 2>&1 || echo "entrypoint: view:cache skipped" >&2

# Any leftover conversion scratch directories from a previous run.
rm -rf storage/app/doc-convert/* 2>/dev/null || true

echo "entrypoint: starting $*"
exec "$@"
