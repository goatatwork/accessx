#!/bin/bash
# 20180323 Goat
# This is intended to be run from the docker HOST. Not within the container

docker exec -it -u www-data accessx_php_1 sed -i 's/^APP_URL=http:\/\/accessx\.goat/APP_URL=http:\/\/10\.200\.200\.1/g' .env
docker exec -it -u www-data accessx_php_1 sed -i 's/\"authHost\": \"http:\/\/accessx\.goat\"/\"authHost\": \"http:\/\/10\.200\.200\.1\"/g' laravel-echo-server.json
docker exec -it -u www-data accessx_php_1 sed -i 's/^DB_PASSWORD=/DB_PASSWORD=1q2w3e4r/g' .env
docker exec -it -u www-data accessx_php_1 composer install --no-dev --ignore-platform-reqs
docker exec -it -u www-data accessx_php_1 php artisan key:generate
docker exec -it -u www-data accessx_php_1 php artisan migrate
docker exec -it -u www-data accessx_php_1 php artisan db:seed
docker exec -it -u www-data accessx_php_1 php artisan passport:install

docker run -d --net=host -v $(pwd)/storage/app/services/dnsmasq:/etc/dnsmasq -v $(pwd)/public/storage/media:/tftpboot -p 67:67 -p 67:67/udp -p 69:69 -p 69:69/udp --rm --name dnsmasq_server goatatwork/dnsmasq-ubuntu:latest
