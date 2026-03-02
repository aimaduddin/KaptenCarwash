#!/bin/bash

set -e

echo "=== Pre-deployment Check Started ==="

echo ""
echo "1. Checking for uncommitted changes..."
if [ -n "$(git status --porcelain)" ]; then
    echo "❌ ERROR: Uncommitted changes detected"
    git status --short
    exit 1
fi
echo "✅ No uncommitted changes"

echo ""
echo "2. Running tests..."
composer test
echo "✅ Tests passed"

echo ""
echo "3. Running code style check..."
vendor/bin/pint --test
if [ $? -ne 0 ]; then
    echo "❌ ERROR: Code style issues found"
    echo "Run: vendor/bin/pint"
    exit 1
fi
echo "✅ Code style check passed"

echo ""
echo "4. Building frontend assets..."
npm run build
if [ $? -ne 0 ]; then
    echo "❌ ERROR: Build failed"
    exit 1
fi
echo "✅ Frontend assets built successfully"

echo ""
echo "5. Checking APP_DEBUG is false..."
if grep -q "APP_DEBUG=true" .env; then
    echo "❌ ERROR: APP_DEBUG is true in .env"
    echo "Set APP_DEBUG=false before deployment"
    exit 1
fi
echo "✅ APP_DEBUG is disabled"

echo ""
echo "=== Pre-deployment Check Passed ==="
echo "Ready for deployment"
