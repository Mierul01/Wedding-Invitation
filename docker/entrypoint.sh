#!/bin/sh
set -e

cd /var/www/html

if [ -z "$APP_KEY" ]; then
  echo "ERROR: APP_KEY is not set. Generate one with: php artisan key:generate --show"
  echo "Then add it as an environment variable on Railway/Render."
  exit 1
fi

# Use Postgres when DATABASE_URL is provided (Railway/Render)
if [ -n "$DATABASE_URL" ]; then
  case "$DATABASE_URL" in
    postgres*|postgresql*) export DB_CONNECTION=pgsql ;;
    mysql*) export DB_CONNECTION=mysql ;;
  esac
fi

# SQLite fallback for quick demos without a managed database
if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ] && [ -z "$DATABASE_URL" ]; then
  export DB_CONNECTION=sqlite
  export DB_DATABASE="${DB_DATABASE:-/var/www/html/database/database.sqlite}"
  mkdir -p "$(dirname "$DB_DATABASE")"
  touch "$DB_DATABASE"
fi

php artisan storage:link --force >/dev/null 2>&1 || true
php artisan migrate --force --no-interaction

# Seed sample wedding data only when the settings table is empty
php -r "
require 'vendor/autoload.php';
\$app = require 'bootstrap/app.php';
\$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
exit(App\Models\WeddingSetting::query()->exists() ? 0 : 1);
" || php artisan db:seed --force --no-interaction

php artisan config:clear
php artisan route:clear
php artisan view:clear

exec php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"
