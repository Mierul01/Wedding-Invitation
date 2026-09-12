#!/bin/sh
set -e

cd /var/www/html

echo "==> Starting Kad Perkahwinan"
echo "==> PORT=${PORT:-8000}"

# Auto-create APP_KEY if Railway/Render variables are missing
if [ -z "$APP_KEY" ]; then
  echo "==> APP_KEY not set — generating one for this deploy"
  export APP_KEY="$(php -r "echo 'base64:'.base64_encode(random_bytes(32));")"
fi

# Railway/Render provide DATABASE_URL; Laravel reads DB_URL
if [ -n "$DATABASE_URL" ] && [ -z "$DB_URL" ]; then
  export DB_URL="$DATABASE_URL"
fi

if [ -n "$DB_URL" ] || [ -n "$DATABASE_URL" ]; then
  URL="${DB_URL:-$DATABASE_URL}"
  case "$URL" in
    postgres*|*postgresql*) export DB_CONNECTION=pgsql ;;
    mysql*) export DB_CONNECTION=mysql ;;
  esac
  echo "==> Using managed database (${DB_CONNECTION})"
else
  export DB_CONNECTION=sqlite
  export DB_DATABASE="${DB_DATABASE:-/var/www/html/database/database.sqlite}"
  mkdir -p "$(dirname "$DB_DATABASE")"
  touch "$DB_DATABASE"
  echo "==> No DATABASE_URL — using SQLite at ${DB_DATABASE}"
fi

export APP_ENV="${APP_ENV:-production}"
export APP_DEBUG="${APP_DEBUG:-false}"
export LOG_CHANNEL="${LOG_CHANNEL:-stderr}"
export SESSION_DRIVER="${SESSION_DRIVER:-database}"
export CACHE_STORE="${CACHE_STORE:-database}"
export QUEUE_CONNECTION="${QUEUE_CONNECTION:-database}"

php artisan storage:link --force >/dev/null 2>&1 || true

echo "==> Running migrations"
php artisan migrate --force --no-interaction

echo "==> Seeding if empty"
php -r "
require 'vendor/autoload.php';
\$app = require 'bootstrap/app.php';
\$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
exit(App\Models\WeddingSetting::query()->exists() ? 0 : 1);
" || php artisan db:seed --force --no-interaction

php artisan config:clear >/dev/null 2>&1 || true
php artisan route:clear >/dev/null 2>&1 || true
php artisan view:clear >/dev/null 2>&1 || true

LISTEN_PORT="${PORT:-8000}"
echo "==> Listening on 0.0.0.0:${LISTEN_PORT}"
exec php artisan serve --host=0.0.0.0 --port="${LISTEN_PORT}"
