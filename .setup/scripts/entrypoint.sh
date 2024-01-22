#!/usr/bin/env sh

php-fpm --daemonize

ln -sf /etc/nginx/sites-available/default.conf /etc/nginx/sites-enabled/default.conf || true \

set -e

if [[ "$1" == -* ]]; then
    set -- nginx -g daemon off; "$@"
fi

exec "$@"