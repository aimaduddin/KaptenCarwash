#!/bin/bash

set -e

echo "=== Post-deployment Setup Started ==="

APP_DIR="${1:-/var/www/kaptencarwash}"
cd "$APP_DIR" || exit 1

echo ""
echo "1. Installing Composer dependencies (production)..."
composer install --optimize-autoloader --no-dev --no-interaction
echo "✅ Dependencies installed"

echo ""
echo "2. Clearing and caching configuration..."
php artisan config:clear
php artisan config:cache
echo "✅ Configuration cached"

echo ""
echo "3. Clearing and caching routes..."
php artisan route:clear
php artisan route:cache
echo "✅ Routes cached"

echo ""
echo "4. Clearing and caching views..."
php artisan view:clear
php artisan view:cache
echo "✅ Views cached"

echo ""
echo "5. Running database migrations..."
php artisan migrate --force
echo "✅ Migrations completed"

echo ""
echo "6. Creating storage link..."
php artisan storage:link
echo "✅ Storage linked"

echo ""
echo "7. Optimizing application..."
php artisan optimize
echo "✅ Application optimized"

echo ""
echo "8. Setting file permissions..."
chown -R www-data:www-data storage bootstrap/cache
find storage -type d -exec chmod 775 {} \;
find storage -type f -exec chmod 664 {} \;
find bootstrap/cache -type d -exec chmod 775 {} \;
find bootstrap/cache -type f -exec chmod 664 {} \;
echo "✅ Permissions set"

echo ""
echo "9. Restarting services..."
sudo systemctl reload php8.2-fpm
sudo systemctl reload nginx
echo "✅ Services restarted"

echo ""
echo "10. Caching events..."
php artisan event:cache
echo "✅ Events cached"

echo ""
echo "=== Post-deployment Setup Completed ==="
echo "Application is ready"
