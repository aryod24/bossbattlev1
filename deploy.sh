#!/bin/bash

echo "🚀 Starting Fast Deployment..."
echo ""

# Check if we're in a git repository
if [ ! -d .git ]; then
    echo "❌ Not a git repository!"
    exit 1
fi

# Store current branch
CURRENT_BRANCH=$(git branch --show-current)
echo "📍 Current branch: $CURRENT_BRANCH"
echo ""

# Stash any local changes
echo "💾 Stashing local changes..."
git stash push -m "Auto-stash before deploy $(date +%Y-%m-%d_%H:%M:%S)"
echo ""

# Pull latest changes
echo "⬇️  Pulling latest changes..."
git pull origin $CURRENT_BRANCH
if [ $? -ne 0 ]; then
    echo "❌ Git pull failed!"
    echo "🔄 Restoring stashed changes..."
    git stash pop
    exit 1
fi
echo "✅ Git pull successful"
echo ""

# Install/update composer dependencies (production mode)
echo "📦 Updating composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction
if [ $? -ne 0 ]; then
    echo "⚠️  Composer install failed, continuing..."
fi
echo ""

# Run migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force
if [ $? -ne 0 ]; then
    echo "⚠️  Migrations failed, continuing..."
fi
echo ""

# Clear all caches
echo "🧹 Clearing old caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
echo ""

# Rebuild caches
echo "⚡ Rebuilding caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo ""

# Optimize application
echo "🔧 Optimizing application..."
php artisan optimize
echo ""

# Restart queue workers if using queues
if command -v supervisorctl &> /dev/null; then
    echo "🔄 Restarting queue workers..."
    php artisan queue:restart
fi

# Pop stashed changes if any
STASH_COUNT=$(git stash list | wc -l)
if [ $STASH_COUNT -gt 0 ]; then
    echo ""
    echo "💾 You have $STASH_COUNT stashed change(s)"
    echo "Run 'git stash pop' to restore your local changes"
fi

echo ""
echo "✅ Deployment completed successfully!"
echo "🎉 Application is ready!"
