#!/bin/bash
# 20180323 Goat
# This is intended to be run from the docker HOST. Not within the container

if [ "$(whoami)" != "root" ]; then
        echo "Script must be run as user root or with sudo."
        exit 1
fi

cp .env.example .env
sed -i -e 's/APP_URL=http:\/\/localhost/APP_URL=http:\/\/10\.200\.200\.1/g' .env
sed -i -e 's/DB_PASSWORD=/DB_PASSWORD=1q2w3e4r/g' .env
sed -i -e 's/"authHost": "http:\/\/accessx\.goat"/"authHost": "http:\/\/10\.200\.200\.1"/g' laravel-echo-server.json
sed -i -e 's/"host": "127\.0\.0\.1"/"host": "redis"/g' laravel-echo-server.json
chown -R www-data.www-data .

# Then permissions need changed
# Then ./develop up -d --build
# Then this....

docker exec -it -u www-data accessx_php_1 composer install --no-dev --ignore-platform-reqs
docker exec -it -u www-data accessx_php_1 php artisan key:generate
docker exec -it -u www-data accessx_php_1 php artisan migrate
docker exec -it -u www-data accessx_php_1 php artisan db:seed
docker exec -it -u www-data accessx_php_1 php artisan passport:install
docker exec -it -u www-data accessx_php_1 php artisan storage:link
docker exec -it -u root accessx_php_1 chown -R www-data.www-data .
docker exec -it -u root accessx_php_1 chown -R www-data.www-data *
docker restart accessx_supervisord_1

# docker run -d --net=host -v $(pwd)/storage/app/services/dnsmasq:/etc/dnsmasq -v $(pwd)/public/storage/media:/tftpboot -p 67:67 -p 67:67/udp -p 69:69 -p 69:69/udp --rm --name dnsmasq_server goatatwork/dnsmasq-ubuntu:latest
