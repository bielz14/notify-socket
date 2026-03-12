#!/bin/bash
set -e

cd /var/www

# If a custom command is passed (reverb, queue worker) — just run it directly
# This allows reverb and queue containers to use same image with different command:
if [ "$1" = "php" ]; then
    exec "$@"
fi

echo "========================================"f
echo "  Socket Notification — App Container"
echo "========================================"

# ── Generate app key if not set ───────────────────────────────────────────────
if [ -z "$APP_KEY" ]; then
    echo "[1/4] Generating application key..."
    php artisan key:generate --force
else
    echo "[1/4] App key already set."
fi

# ── Wait for MySQL ────────────────────────────────────────────────────────────
echo "[2/4] Waiting for MySQL..."
until php -r "
    \$conn = @mysqli_connect(
        getenv('DB_HOST'),
        getenv('DB_USERNAME'),
        getenv('DB_PASSWORD'),
        getenv('DB_DATABASE'),
        (int)getenv('DB_PORT')
    );
    if (\$conn) { exit(0); } exit(1);
"; do
    echo "  MySQL not ready yet, retrying in 2s..."
    sleep 2
done
echo "  MySQL is ready!"

# ── Run migrations ────────────────────────────────────────────────────────────
echo "[3/4] Running migrations..."
php artisan migrate --force --graceful

# ── Optimize ─────────────────────────────────────────────────────────────────
echo "[4/4] Optimizing..."
php artisan config:clear
php artisan optimize
php artisan storage:link --force 2>/dev/null || true
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

echo ""
echo "  → Starting PHP-FPM + Nginx"
echo "  → Web: http://localhost:8000"
echo "========================================"

# ── Start PHP-FPM in background, then Nginx in foreground ────────────────────
php-fpm -D
exec nginx -g "daemon off;"
