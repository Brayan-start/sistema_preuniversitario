#!/bin/bash
# Laravel deployment script for Render
# Runs automatically on container start via richarvey/nginx-php-fpm

echo "=== Laravel Deployment ==="

# Generate app key if not set
if grep -q "APP_KEY=$" .env 2>/dev/null || [ -z "$APP_KEY" ]; then
    php artisan key:generate --force --no-interaction
    echo "✓ APP_KEY generated"
fi

# Cache Laravel config, routes, and views
php artisan config:cache --no-interaction 2>&1 || echo "⚠ config:cache skipped (may be expected)"
php artisan route:cache --no-interaction 2>&1 || echo "⚠ route:cache skipped"
php artisan view:cache --no-interaction 2>&1 || echo "⚠ view:cache skipped"

# Create storage symlink
php artisan storage:link --no-interaction 2>&1 || echo "⚠ storage:link skipped (may already exist)"

# Run database migrations
php artisan migrate --force --no-interaction 2>&1 || echo "⚠ migrate failed"

echo "=== Deployment complete ==="
