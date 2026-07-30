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

export PORT="${PORT:-80}"
export LOG_CHANNEL="${LOG_CHANNEL:-stderr}"

envsubst '${PORT}' < /etc/nginx/conf.d/default.conf.template > /etc/nginx/conf.d/default.conf

if [ -z "${APP_KEY:-}" ] || [ "${APP_KEY}" = "base64:" ]; then
    php artisan key:generate --force --no-interaction
fi

chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache

wait_for_database() {
    retries=0
    max_retries=15

    echo "Checking database connection..."
    until php -r "
        try {
            if (\$url = getenv('DB_URL')) {
                \$parts = parse_url(\$url);
                if (\$parts === false || !isset(\$parts['host'])) {
                    exit(1);
                }

                \$dsn = sprintf(
                    'pgsql:host=%s;port=%s;dbname=%s',
                    \$parts['host'],
                    \$parts['port'] ?? 5432,
                    ltrim(\$parts['path'] ?? '', '/')
                );

                new PDO(
                    \$dsn,
                    \$parts['user'] ?? 'postgres',
                    \$parts['pass'] ?? '',
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
            } else {
                \$connection = getenv('DB_CONNECTION') ?: 'pgsql';
                \$host = getenv('DB_HOST');
                \$port = getenv('DB_PORT') ?: '5432';
                \$database = getenv('DB_DATABASE') ?: 'postgres';
                \$username = getenv('DB_USERNAME') ?: 'postgres';
                \$password = getenv('DB_PASSWORD') ?: '';

                if (!\$host || \$host === '127.0.0.1') {
                    exit(0);
                }

                if (\$connection === 'pgsql') {
                    \$dsn = \"pgsql:host={\$host};port={\$port};dbname={\$database}\";
                } else {
                    \$dsn = \"mysql:host={\$host};port={\$port}\";
                }

                new PDO(\$dsn, \$username, \$password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            }

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
    echo "ERROR: Database not reachable. Check DB_URL or DB_* variables."
    exit 1
fi

echo "Database is ready."

php artisan migrate --force --no-interaction

php artisan config:cache --no-interaction
php artisan route:cache --no-interaction
php artisan view:cache --no-interaction

echo "Starting php-fpm, nginx on port ${PORT}, and queue worker..."
exec "$@"
