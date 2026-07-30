#!/bin/sh
set -e

cd /var/www/html

if [ ! -f .env ]; then
    cp .env.example .env
fi

# Strip stray "{" / "}" from malformed Railway variable references
sanitize_railway_var() {
    value=$1
    case "$value" in
        \{*) value=${value#\{} ;;
    esac
    case "$value" in
        *\}) value=${value%\}} ;;
    esac
    printf '%s' "$value"
}

# Map Railway Postgres variables to Laravel DB_* when not explicitly set
export DB_CONNECTION="${DB_CONNECTION:-pgsql}"
export DB_HOST="$(sanitize_railway_var "${DB_HOST:-${PGHOST:-}}")"
export DB_PORT="$(sanitize_railway_var "${DB_PORT:-${PGPORT:-5432}}")"
export DB_USERNAME="$(sanitize_railway_var "${DB_USERNAME:-${PGUSER:-postgres}}")"
export DB_PASSWORD="$(sanitize_railway_var "${DB_PASSWORD:-${PGPASSWORD:-}}")"
export DB_DATABASE="$(sanitize_railway_var "${DB_DATABASE:-${PGDATABASE:-railway}}")"
export DB_URL="$(sanitize_railway_var "${DB_URL:-${DATABASE_URL:-}}")"

# DB_HOST must be a hostname — not a full URL (common Railway misconfiguration)
case "${DB_HOST:-}" in
    postgres://*|postgresql://*)
        echo "WARNING: DB_HOST contains a full database URL."
        echo "  Move it to DB_URL and remove DB_HOST, DB_PORT, DB_USERNAME, DB_PASSWORD, DB_DATABASE."
        if [ -z "${DB_URL:-}" ]; then
            export DB_URL="${DB_HOST}"
        fi
        unset DB_HOST
        unset DB_PORT
        unset DB_USERNAME
        unset DB_PASSWORD
        unset DB_DATABASE
        ;;
esac

# When using DB_URL, individual DB_* vars must not override it
if [ -n "${DB_URL:-}" ]; then
    unset DB_HOST
    unset DB_PORT
    unset DB_USERNAME
    unset DB_PASSWORD
    unset DB_DATABASE
fi

# Railway Postgres requires SSL
if [ -n "${RAILWAY_ENVIRONMENT_ID:-}" ] || [ -n "${PGHOST:-}" ] || [ -n "${DB_URL:-}" ]; then
    export DB_SSLMODE="${DB_SSLMODE:-require}"
fi

if [ -z "${APP_URL:-}" ] && [ -n "${RAILWAY_PUBLIC_DOMAIN:-}" ]; then
    export APP_URL="https://${RAILWAY_PUBLIC_DOMAIN}"
fi

# Railway injects PORT automatically — do NOT hardcode PORT=80 in Railway variables.
# The domain "target port" in Networking settings must match this value.
if [ -n "${RAILWAY_ENVIRONMENT_ID:-}" ] || [ -n "${RAILWAY_PUBLIC_DOMAIN:-}" ]; then
    if [ -z "${PORT:-}" ]; then
        echo "ERROR: PORT is not set. Remove any manual PORT variable and let Railway inject it."
        exit 1
    fi
    echo "Railway PORT=${PORT} — ensure Networking target port is also ${PORT}."
else
    export PORT="${PORT:-80}"
fi

export LOG_CHANNEL="${LOG_CHANNEL:-stderr}"

envsubst '${PORT}' < /etc/nginx/conf.d/default.conf.template > /etc/nginx/conf.d/default.conf
echo "Nginx configured on 0.0.0.0:${PORT}"

if [ -z "${APP_KEY:-}" ] || [ "${APP_KEY}" = "base64:" ]; then
    php artisan key:generate --force --no-interaction
fi

chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache

log_database_config() {
    if [ -n "${DB_URL:-}" ]; then
        echo "Database: using DB_URL"
    elif [ -n "${DB_HOST:-}" ] && [ "${DB_HOST}" != "127.0.0.1" ]; then
        echo "Database: ${DB_CONNECTION}://${DB_HOST}:${DB_PORT}/${DB_DATABASE}"
    else
        echo "ERROR: No database configured."
        echo "  Set only: DB_URL=\${{Postgres.DATABASE_URL}}"
        echo "  Do NOT put DATABASE_URL in DB_HOST."
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
