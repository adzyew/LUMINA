
#!/usr/bin/env bash
set -eu

cd /var/www/html

mkdir -p \
  storage/framework/cache \
  storage/framework/sessions \
  storage/framework/views \
  storage/logs \
  bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache

# Discover installed Laravel packages at runtime.
php artisan package:discover --ansi

# Cache configuration using Render's runtime environment variables.
php artisan config:cache

# Start PHP-FPM and Nginx.
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
