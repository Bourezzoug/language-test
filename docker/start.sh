#!/bin/bash
set -e

cd /var/www/html

touch database/database.sqlite

php artisan storage:link || true
php artisan config:clear || true
php artisan migrate --force || true
php artisan config:cache || true

apache2-foreground