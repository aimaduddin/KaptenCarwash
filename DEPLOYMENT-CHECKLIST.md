# Deployment Checklist - Production

## PDF Receipt Setup

### Package Requirements

The PDF receipt functionality uses `dompdf/dompdf`, a pure PHP library. **No external binaries or system-level installations are required.**

### Verify Package

Ensure the package is installed:

```bash
composer show dompdf/dompdf
```

If not installed, run:

```bash
composer require dompdf/dompdf
```

### Test PDF Generation

After deployment, test the PDF download functionality:

1. Complete a booking through the booking wizard
2. Go to the success page
3. Click the "Download Receipt" button
4. Verify that the PDF downloads correctly with all booking details

### Common Issues

#### PDF Download Fails / Returns 500 Error

**Cause**: dompdf package not installed or class not found

**Solution**:
```bash
composer require dompdf/dompdf
composer dump-autoload
```

#### PDF Generation Slow or Times Out

**Cause**: Complex HTML or large images causing rendering delays

**Solution**:
- Optimize the receipt template
- Reduce image sizes
- Increase PHP execution time in `php.ini`
- Consider using image placeholders for testing

#### PDF Shows Garbled Text or Incorrect Characters

**Cause**: Font encoding issues

**Solution**:
- Ensure the receipt template uses UTF-8 encoding
- Check that fonts support the required characters
- Consider embedding fonts in the PDF

#### Permission Denied on Writing PDF

**Cause**: Temporary directory not writable by web server

**Solution**:
```bash
# Check temporary directory permissions
ls -la /tmp

# Set appropriate permissions if needed
chmod 755 /tmp
```

### Performance Considerations

dompdf is a pure PHP solution and may be slower than alternatives like wkhtmltopdf for complex documents. For production with high volume, consider:

1. **Caching**: Cache generated PDFs to avoid regenerating the same receipt
2. **Queue Processing**: Generate PDFs in the background using Laravel queues
3. **Alternative Libraries**: Consider `tcpdf` or other libraries if performance is critical

### 2. Install Dependencies

```bash
# Install production dependencies without dev packages
composer install --optimize-autoloader --no-dev

# Build frontend assets (if using Vite)
npm run build
```

### 3. Database Setup

```bash
# Run migrations
php artisan migrate --force

# Optional: Seed database
php artisan db:seed --force
```

### 4. Set File Permissions

```bash
# Set proper permissions
chmod -R 755 storage bootstrap/cache

# Make storage writable
chown -R www-data:www-data storage bootstrap/cache

# Set permissions for directories
find storage -type d -exec chmod 775 {} \;

# Set permissions for files
find storage -type f -exec chmod 664 {} \;
```

### 5. Cache Configuration

```bash
# Clear and cache configuration
php artisan config:clear
php artisan config:cache

# Clear and cache routes
php artisan route:clear
php artisan route:cache

# Clear and cache views
php artisan view:clear
php artisan view:cache

# Cache events and routes
php artisan event:cache
```

### 6. Link Storage (for public file access)

```bash
php artisan storage:link
```

### 7. Queue Worker (if using queues)

```bash
# Install supervisor for queue management
sudo apt-get install supervisor

# Create supervisor config at /etc/supervisor/conf.d/laravel-worker.conf
# Add configuration for queue worker
# Reload supervisor
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

### 8. Cron Job Setup

```bash
# Add to crontab: * * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1
crontab -e
```

### 9. Web Server Configuration

**Nginx:**
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path-to-your-project/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.x-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

**Apache:**
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /path-to-your-project/public

    <Directory /path-to-your-project/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

### 10. SSL Certificate (HTTPS)

```bash
# Install certbot and get free SSL certificate
sudo apt-get install certbot python3-certbot-nginx
sudo certbot --nginx -d yourdomain.com

# Auto-renewal is automatically set up
# Test renewal: sudo certbot renew --dry-run
```

## PDF Receipt Setup

### wkhtmltopdf Installation

The PDF receipt functionality requires the `wkhtmltopdf` binary to be installed on the server.

#### macOS
```bash
brew install wkhtmltopdf
```

#### Ubuntu/Debian
```bash
sudo apt-get update
sudo apt-get install wkhtmltopdf
```

#### CentOS/RHEL
```bash
sudo yum install wkhtmltopdf
```

#### Verify Installation
```bash
wkhtmltopdf --version
```

### Configuration

After installing wkhtmltopdf, verify the binary path in `config/snappy.php`:

```php
'pdf' => [
    'enabled' => true,
    'binary'  => env('WKHTMLTOPDF_BINARY', '/usr/local/bin/wkhtmltopdf'),
    'options' => [],
],
```

If the binary is in a different location, update the path accordingly.

### Test PDF Generation

After deployment, test the PDF download functionality:

1. Complete a booking through the booking wizard
2. Go to the success page
3. Click the "Download Receipt" button
4. Verify that the PDF downloads correctly with all booking details

### Common Issues

#### PDF Download Fails / Returns 500 Error

**Cause**: wkhtmltopdf not installed or not in expected location

**Solution**:
- Install wkhtmltopdf using the appropriate command for your OS
- Verify installation with `wkhtmltopdf --version`
- Check server logs for specific error messages
- Update `config/snappy.php` with correct binary path if needed

#### Permission Denied

**Cause**: Web server doesn't have permission to execute wkhtmltopdf

**Solution**:
```bash
chmod +x /path/to/wkhtmltopdf
```

#### Binary Not Found Error

**Cause**: wkhtmltopdf binary is not in system PATH

**Solution**:
- Add the binary location to `config/snappy.php`
- Or add to system PATH by creating symlink:
  ```bash
  sudo ln -s /path/to/wkhtmltopdf /usr/local/bin/wkhtmltopdf
  ```

## Additional Deployment Notes

### Environment Variables

Ensure these are set in your production `.env` file:

```env
# PDF Configuration (if using custom paths)
WKHTMLTOPDF_BINARY=/usr/local/bin/wkhtmltopdf
```

### Dependencies

The following package is included in `composer.json`:
- `dompdf/dompdf` - Pure PHP PDF generation library

No external binaries or system-level installations are required.

### Cache

After deployment, clear and cache configurations:

```bash
php artisan config:clear
php artisan config:cache
php artisan route:clear
php artisan route:cache
php artisan view:clear
php artisan view:cache
```

### Session Configuration

For guest booking receipt access, ensure session configuration is properly set in `config/session.php`:
- File-based storage or Redis recommended for production
- Appropriate session lifetime set
- Secure cookie configuration

## Security Considerations

The receipt download includes these security measures:
- Authenticated users can only download their own bookings
- Guest bookings tracked in session with expiration
- 403 error for unauthorized access attempts
- No sensitive data exposed in PDF

## Monitoring

Monitor these metrics after deployment:
- PDF download success rate
- wkhtmltopdf execution time
- Any 403 or 500 errors on receipt download endpoint

## Post-Deployment Checklist

### 1. Verify Application Health

- [ ] Homepage loads correctly
- [ ] All pages return 200 status
- [ ] Static assets (CSS, JS, images) load
- [ ] No JavaScript errors in browser console
- [ ] Application logs are clean

### 2. Test Core Functionality

- [ ] User registration works
- [ ] User login/logout works
- [ ] Booking wizard completes successfully
- [ ] Payment flow (dummy gateway) works
- [ ] PDF receipt downloads correctly
- [ ] Admin panel accessible for authorized users
- [ ] Admin features (calendar, kanban, settings) work

### 3. Test Error Handling

- [ ] 404 page shows correctly
- [ ] 500 page shows appropriately (if errors occur)
- [ ] Form validation displays errors
- [ ] Unauthorized access shows appropriate messages

### 4. Performance Checks

- [ ] Page load times are acceptable (< 2 seconds)
- [ ] Database queries are optimized
- [ ] No N+1 query issues
- [ ] Caching is working (routes, config, views)

### 5. Security Verification

- [ ] APP_DEBUG=false in production
- [ ] Only necessary ports open (80, 443, SSH)
- [ ] CSRF protection enabled
- [ ] SQL injection protection working
- [ ] XSS protection working
- [ ] File upload restrictions in place
- [ ] Secure session configuration

### 6. Email Configuration

- [ ] Email credentials configured
- [ ] Test email sends successfully
- [ ] Email templates render correctly
- [ ] Notifications work (if implemented)

### 7. Backup Strategy

- [ ] Automated database backups configured
- [ ] Backup storage location secured
- [ ] Backup retention policy set
- [ ] Backup restoration tested

### 8. Logging & Monitoring

- [ ] Log rotation configured
- [ ] Error monitoring setup (e.g., Sentry, Bugsnag)
- [ ] Performance monitoring setup (e.g., New Relic, Datadog)
- [ ] Uptime monitoring configured

### 9. CDN & Caching (Optional)

- [ ] CDN configured for static assets
- [ ] Browser caching headers set
- [ ] Cloudflare or similar setup (if using)

### 10. Payment Gateway

- [ ] Switch from dummy to production payment gateway
- [ ] Payment gateway API keys configured
- [ ] Webhook endpoints configured
- [ ] Payment flow tested with real payment

## Troubleshooting

### Common Issues

**Application returns 500 error**
```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Check PHP error logs
tail -f /var/log/php8.x-fpm.log

# Check web server logs
tail -f /var/log/nginx/error.log  # Nginx
tail -f /var/log/apache2/error.log  # Apache
```

**Assets not loading**
```bash
# Clear cache
php artisan cache:clear
php artisan view:clear

# Rebuild assets
npm run build

# Check permissions
ls -la public/
```

**Database connection issues**
```bash
# Verify database credentials in .env
# Check database is running
sudo systemctl status postgresql  # PostgreSQL
sudo systemctl status mysql      # MySQL

# Test connection from server
psql -h localhost -U username -d database  # PostgreSQL
mysql -u username -p database              # MySQL
```

**Permissions issues**
```bash
# Reset storage permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Fix storage link
php artisan storage:unlink
php artisan storage:link
```

**Queue not processing**
```bash
# Check queue worker status
sudo supervisorctl status

# Restart queue worker
sudo supervisorctl restart laravel-worker:*

# Check queue logs
tail -f storage/logs/queue.log
```

## Maintenance

### Regular Tasks

**Weekly:**
- Review and rotate logs
- Check disk space usage
- Review error logs for issues

**Monthly:**
- Review and optimize database
- Test backup restoration
- Review security updates
- Update dependencies (composer, npm)

**Quarterly:**
- Security audit
- Performance review
- Dependency updates and testing
- Disaster recovery drill

### Dependency Updates

```bash
# Check for Laravel updates
composer update laravel/framework --with-all-dependencies

# Check for security vulnerabilities
composer audit

# Update npm packages
npm update

# Test thoroughly in staging before production
```

## Rollback Procedure

If deployment causes critical issues:

1. **Revert code changes**
```bash
git revert <commit-hash>
git push origin main
```

2. **Rollback database**
```bash
php artisan migrate:rollback --step=1
```

3. **Clear caches**
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

4. **Restore from backup** (if database was modified)

## Support & Documentation

- Laravel Documentation: https://laravel.com/docs
- wkhtmltopdf Documentation: https://wkhtmltopdf.org/
- Server OS Documentation (Ubuntu/Debian/CentOS)
- Web Server Documentation (Nginx/Apache)
