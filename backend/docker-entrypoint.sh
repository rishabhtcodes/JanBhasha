#!/bin/sh
set -e

# ── Laravel boot tasks (run at container start so env vars are injected) ────
echo "→ Caching Laravel config..."
php artisan config:cache

echo "→ Caching routes..."
php artisan route:cache

echo "→ Caching views..."
php artisan view:cache

echo "→ Boot complete. Starting Apache..."
exec "$@"
