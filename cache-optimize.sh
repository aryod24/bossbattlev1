#!/bin/bash

echo "🚀 Optimizing Laravel Cache..."
echo ""

# Cache configuration
echo "📦 Caching configuration..."
php artisan config:cache
if [ $? -eq 0 ]; then
    echo "✅ Config cached successfully"
else
    echo "❌ Config cache failed"
    exit 1
fi

echo ""

# Cache routes
echo "🛣️  Caching routes..."
php artisan route:cache
if [ $? -eq 0 ]; then
    echo "✅ Routes cached successfully"
else
    echo "❌ Routes cache failed"
    exit 1
fi

echo ""

# Cache views (already cached, but refresh it)
echo "👁️  Caching views..."
php artisan view:cache
if [ $? -eq 0 ]; then
    echo "✅ Views cached successfully"
else
    echo "❌ Views cache failed"
    exit 1
fi

echo ""
echo "✨ All caches optimized successfully!"
echo ""
echo "Run 'php artisan optimize' for additional optimizations"
