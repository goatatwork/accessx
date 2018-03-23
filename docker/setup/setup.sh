#!/bin/bash
composer install --no-dev --ignore-platform-reqs \
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan passport:install
