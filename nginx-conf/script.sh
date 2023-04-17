#!/usr/bin/env bash

#cp /var/www/html/.env.example /var/www/html/.env
chmod 777 -R /var/www/html
composer install
php artisan key:generate --force
php artisan cache:clear
php artisan config:clear
php artisan migrate --force
php artisan optimize
service nginx start
php-fpm
