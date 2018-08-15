#!/bin/bash
# 20180323 Goat
# This is intended to be run from the docker HOST. Not within the container

if [ "$(whoami)" != "root" ]; then
        echo "Script must be run as user root or with sudo."
        exit 1
fi

chown -R www-data.www-data .

# Now let's start the containers
./goldaccess.sh up -d --build

sleep 3

# Prep the application
#
# Copy .env.example to live .env
docker exec -it -u www-data accessx_php_1 cp .env.example .env

# Set db password in .env
docker exec -it -u www-data accessx_php_1 sed -i -e 's/DB_PASSWORD=/DB_PASSWORD=1q2w3e4r/g' .env

# So that composer can have it's working directory
docker exec -it -u root accessx_php_1 mkdir /var/www/.composer
docker exec -it -u root accessx_php_1 chown -R www-data.www-data /var/www/.composer

# Install dependencies
docker exec -it -u www-data accessx_php_1 composer install --no-dev --ignore-platform-reqs

# Configure GoldAccess
docker exec -it -u www-data accessx_php_1 php artisan key:generate
docker exec -it -u www-data accessx_php_1 php artisan migrate
docker exec -it -u www-data accessx_php_1 php artisan db:seed
docker exec -it -u www-data accessx_php_1 php artisan passport:install
docker exec -it -u www-data accessx_php_1 php artisan storage:link

# So that the www-data user can use tinker
docker exec -it -u root accessx_php_1 mkdir /var/www/.config
docker exec -it -u root accessx_php_1 chown -R www-data.www-data /var/www/.config

# Restart a few things now that everything is configured
docker restart accessx_horizon-supervisor_1

echo "All finished."

# docker run -d --net=host -v $(pwd)/storage/app/services/dnsmasq:/etc/dnsmasq -v $(pwd)/public/storage/media:/tftpboot -p 67:67 -p 67:67/udp -p 69:69 -p 69:69/udp --rm --name dnsmasq_server goatatwork/dnsmasq-ubuntu:latest
