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
sed -i -e 's/"http:\/\/nginx\/api\/dnsmasq\/events"/"http:\/\/10\.200\.200\.1\/api\/dnsmasq\/events"/g' storage/app/services/dnsmasq/dhcp-script.sh
touch storage/app/services/dnsmasq/leases/dnsmasq.leases
chown -R www-data.www-data .

# Now let's start the containers
#./develop.sh up -d --build

cd docker/dnsmasq
docker build -t goldaccess/dnsmasq:production -t goldaccess/dnsmasq:1.0 .
cd ../..
docker build -f Dockerfile-nginx-swarm -t goldaccess/nginx:production -t goldaccess/nginx:1.0 .
docker build -f Dockerfile-php-swarm -t goldaccess/php-fpm:production -t goldaccess/php-fpm:1.0 .
docker build -f Dockefile-echo-swarm -t goldaccess/laravel-echo-server:production -t goldaccess/laravel-echo-server:1.0 .
docker build -f Dockefile-horizon-swarm -t goldaccess/laravel-horizon-server:production -t goldaccess/laravel-horizon-server:1.0 .

# Get our Percona proxy
docker pull perconalab/proxysql

# Get our name/data store for Percona because it's lame and doesn't support
# redis
docker pull quay.io/coreos/etcd

# Get the Percona Cluster Db
percona/percona-xtradb-cluster:5.7

# Prep the application
# docker exec -it -u www-data accessx_php_1 composer install --no-dev --ignore-platform-reqs
# docker exec -it -u www-data accessx_php_1 php artisan key:generate
# docker exec -it -u www-data accessx_php_1 php artisan migrate
# docker exec -it -u www-data accessx_php_1 php artisan db:seed
# docker exec -it -u www-data accessx_php_1 php artisan passport:install
# docker exec -it -u www-data accessx_php_1 php artisan storage:link
# docker exec -it -u root accessx_php_1 chown -R www-data.www-data .
# docker exec -it -u root accessx_php_1 chown -R www-data.www-data *
# So that the www-data user can use tinker
# docker exec -it -u root accessx_php_1 mkdir /var/www/.config
# docker exec -it -u root accessx_php_1 chown -R www-data.www-data /var/www/.config

# Restart a few things now that everything is configured
# docker restart accessx_horizon-supervisor_1

# echo "All finished. Make sure to place one of your API keys into storage/app/services/dnsmasq/dhcp-script.sh"

# docker run -d --net=host -v $(pwd)/storage/app/services/dnsmasq:/etc/dnsmasq -v $(pwd)/public/storage/media:/tftpboot -p 67:67 -p 67:67/udp -p 69:69 -p 69:69/udp --rm --name dnsmasq_server goatatwork/dnsmasq-ubuntu:latest
