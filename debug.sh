#!/bin/bash

echo "=== Astro Theme Debug Script ==="
echo ""

# Check if Pterodactyl directory exists
if [ ! -d "/var/www/pterodactyl" ]; then
    echo "❌ Pterodactyl directory not found"
    exit 1
fi

cd /var/www/pterodactyl

echo "1. Checking storage/logs directory..."
ls -la storage/logs/ 2>/dev/null || echo "storage/logs directory not found"
echo ""

echo "2. Finding log files..."
find storage/logs -name "*.log" -type f 2>/dev/null | head -5
echo ""

echo "3. Checking latest log (if exists)..."
if [ -f "storage/logs/laravel.log" ]; then
    echo "=== Last 30 lines of laravel.log ==="
    tail -30 storage/logs/laravel.log
else
    echo "laravel.log not found, checking for other log files..."
    LATEST_LOG=$(find storage/logs -name "*.log" -type f -printf '%T@ %p\n' 2>/dev/null | sort -n | tail -1 | cut -f2- -d" ")
    if [ -n "$LATEST_LOG" ]; then
        echo "Found: $LATEST_LOG"
        echo "=== Last 30 lines ==="
        tail -30 "$LATEST_LOG"
    else
        echo "No log files found in storage/logs/"
    fi
fi
echo ""

echo "4. Checking file permissions..."
ls -la .blueprint/extensions/astrotheme/ 2>/dev/null || echo "Extension not installed in .blueprint/extensions/"
echo ""

echo "5. Checking if extension is enabled..."
php artisan tinker --execute="echo \BlueprintFramework\Services\Blueprint\BlueprintService::class;" 2>/dev/null || echo "Cannot run tinker"
echo ""

echo "6. Clearing all caches..."
php artisan cache:clear 2>&1
php artisan view:clear 2>&1
php artisan config:clear 2>&1
php artisan route:clear 2>&1
echo ""

echo "7. Checking PHP syntax of extension files..."
php -l .blueprint/extensions/astrotheme/admin/Controller.php 2>&1 || echo "Controller.php has syntax errors"
echo ""

echo "=== Debug complete ==="
