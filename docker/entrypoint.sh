#!/bin/bash

if [ ! -f "vendor/autoload.php" ]; then
    composer install --no-progress --no-interaction
fi

if [ ! -f ".env" ]; then
    echo "Creating env file for env $APP_ENV"
    cp .env.example .env
else
    echo "env file exists."
fi

php artisan migrate
php artisan key:generate
php artisan --env=testing key:generate

php artisan clear
php artisan event:clear
php artisan optimize:clear
php artisan queue:clear
php artisan schedule:clear-cache
php artisan config:clear
php artisan route:clear
php artisan cache:clear

php-fpm -D
nginx -g "daemon off;"

