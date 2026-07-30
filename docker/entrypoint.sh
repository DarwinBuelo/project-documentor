#!/bin/sh
set -e

cd /var/www/html

if [ ! -f .env ]; then
    cp .env.example .env
fi

# Map Railway Postgres variables to Laravel DB_* when not explicitly set
export DB_CONNECTION="${DB_CONNECTION:-pgsql}"
export DB_HOST="${DB_HOST:-${PGHOST:-}}"
export DB_PORT="${DB_PORT:-${PGPORT:-5432}}"
export DB_USERNAME="${DB_USERNAME:-${PGUSER:-postgres}}"
export DB_PASSWORD="${DB_PASSWORD:-${PGPASSWORD:-}}"
export DB_DATABASE="${DB_DATABASE:-${PGDATABASE:-railway}}"
export DB_URL="${DB_URL:-${DATABASE_URL:-}}"

if [ -z "${APP_URL:-}" ] && [ -n "${RAILWAY_PUBLIC_DOMAIN:-}" ]; then
    export APP_URL="https://${RAILWAY_PUBLIC_DOMAIN}"
fi

export LOG_CHANNEL="${LOG_CHANNEL:-stderr}"

wait_for_database() {
    retries=0
    max_retries=30

    if [ -n "${DB_URL:-}" ]; then
        echo "Waiting for database via DB_URL..."
        until php -r "
            try {
                new PDO(getenv('DB_URL'));
                exit(0);
            } catch (Throwable \$e) {
                exit(1);
            }
        " 2>/dev/null; do
            retries=$((retries + 1))
            if [ "$retries" -ge "$max_retries" ]; then
                return 1
            fi
            sleep 2
        done
        return 0
    fi

    if [ -z "${DB_HOST:-}" ] || [ "${DB_HOST}" = "127.0.0.1" ]; then
        return 0
    fi

    echo "Waiting for database at ${DB_HOST}:${DB_PORT}..."
    until php -r "
        try {
            \$connection = getenv('DB_CONNECTION') ?: 'pgsql';
            \$host = getenv('DB_HOST');
            \$port = getenv('DB_PORT') ?: '5432';
            \$database = getenv('DB_DATABASE') ?: 'postgres';
            \$username = getenv('DB_USERNAME') ?: 'postgres';
            \$password = getenv('DB_PASSWORD') ?: '';

            if (\$connection === 'pgsql') {
                \$dsn = \"pgsql:host={\$host};port={\$port};dbname={\$database}\";
            } else {
                \$dsn = \"mysql:host={\$host};port={\$port}\";
            }

            new PDO(\$dsn, \$username, \$password);
            exit(0);
        } catch (Throwable \$e) {
            exit(1);
        }
    " 2>/dev/null; do
        retries=$((retries + 1))
        if [ "$retries" -ge "$max_retries" ]; then
            return 1
        fi
        sleep 2
    done

    return 0
}

if ! wait_for_database; then
    echo "WARNING: Database not reachable yet. Starting web server anyway."
else
    echo "Database is ready."
fi

if [ -z "${APP_KEY:-}" ] || [ "${APP_KEY}" = "base64:" ]; then
    php artisan key:generate --force --no-interaction
else
    grep -q "^APP_KEY=" .env && sed -i "s|^APP_KEY=.*|APP_KEY=${APP_KEY}|" .env || echo "APP_KEY=${APP_KEY}" >> .env
fi

php artisan migrate --force --no-interaction || echo "WARNING: migrations failed; check database configuration."

php artisan config:cache --no-interaction
php artisan route:cache --no-interaction
php artisan view:cache --no-interaction

chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache

echo "Starting php-fpm, nginx on port 80, and queue worker..."
exec "$@"
