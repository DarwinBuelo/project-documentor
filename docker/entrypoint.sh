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

# Railway Postgres requires SSL
if [ -n "${RAILWAY_ENVIRONMENT_ID:-}" ] || [ -n "${PGHOST:-}" ] || [ -n "${DB_URL:-}" ]; then
    export DB_SSLMODE="${DB_SSLMODE:-require}"
fi

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

log_database_config() {
    if [ -n "${DB_URL:-}" ]; then
        echo "Database: using DB_URL (Railway DATABASE_URL)"
    elif [ -n "${DB_HOST:-}" ] && [ "${DB_HOST}" != "127.0.0.1" ]; then
        echo "Database: ${DB_CONNECTION}://${DB_HOST}:${DB_PORT}/${DB_DATABASE}"
    else
        echo "ERROR: No database configured."
        echo "  Set DB_URL=\${{Postgres.DATABASE_URL}} in Railway service variables."
        echo "  Replace 'Postgres' with your Postgres service name."
    fi
}

run_initialization() {
    log_database_config

    attempts=0
    max_attempts=30

    while [ "$attempts" -lt "$max_attempts" ]; do
        attempts=$((attempts + 1))
        echo "Running migrations (attempt ${attempts}/${max_attempts})..."

        if php artisan migrate --force --no-interaction; then
            echo "Migrations complete."

            php artisan config:cache --no-interaction
            php artisan route:cache --no-interaction
            php artisan view:cache --no-interaction

            supervisorctl -c /etc/supervisor/conf.d/supervisord.conf start queue || true
            return 0
        fi

        echo "Migration failed, retrying in 10 seconds..."
        sleep 10
    done

    echo "ERROR: Could not run migrations after ${max_attempts} attempts."
    return 1
}

run_initialization &

echo "Starting php-fpm and nginx on port ${PORT}..."
exec "$@"
