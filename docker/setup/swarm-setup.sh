#!/bin/bash
# 20180323 Goat
# This is intended to be run from the docker HOST. Not within the container

# This script assumes a registry running at 10.200.200.1

if [ "$(whoami)" != "root" ]; then
        echo "Script must be run as user root or with sudo."
        exit 1
fi

# Start Registry
# echo "Starting local container registry"
echo ""
echo "Starting registry"
echo ""
docker run -d -p 5000:5000 --name registry registry:2

# echo "Building DHCP image"
# docker build -f Dockerfile-dnsmasq-swarm -t 10.200.200.1:5000/dnsmasq:production .
# echo "Building HTTP image"
# docker build -f Dockerfile-nginx-swarm -t 10.200.200.1:5000/nginx:production .
# echo "Building PHP-FPM image"
# docker build -f Dockerfile-php-swarm -t 10.200.200.1:5000/php-fpm:production .
# echo "Building Laravel Echo Server image"
# docker build -f Dockerfile-echo-swarm -t 10.200.200.1:5000/laravel-echo-server:production .
# echo "Building Laravel Horizon Supervisor image"
# docker build -f Dockerfile-horizon-swarm -t 10.200.200.1:5000/laravel-horizon-server:production .

# Get our redis:alpine
echo ""
echo "Pulling redis:alpine"
echo ""
docker pull redis:alpine
docker tag redis:alpine 10.200.200.1:5000/redis:production

# Get our Percona proxy
echo ""
echo "Pulling perconalab/proxysql"
echo ""
docker pull perconalab/proxysql
docker tag perconalab/proxysql:latest 10.200.200.1:5000/proxysql:production

# Get our name/data store for Percona because it's lame and doesn't support
# redis
echo ""
echo "Pulling quay.io/coreos/etcd"
echo ""
docker pull quay.io/coreos/etcd
docker tag quay.io/coreos/etcd:latest 10.200.200.1:5000/etcd:production

# Get the Percona Cluster Db
echo ""
echo "Pulling percona/percona-xtradb-cluster:5.7"
echo ""
docker pull percona/percona-xtradb-cluster:5.7
docker tag percona/percona-xtradb-cluster:5.7 10.200.200.1:5000/percona-xtradb-cluster:production

echo ""
echo "Pushing images to registry"
echo ""
docker push 10.200.200.1:5000/etcd:production
docker push 10.200.200.1:5000/redis:production
# docker push 10.200.200.1:5000/nginx:production
# docker push 10.200.200.1:5000/dnsmasq:production
# docker push 10.200.200.1:5000/php-fpm:production
docker push 10.200.200.1:5000/proxysql:production
# docker push 10.200.200.1:5000/laravel-echo-server:production
# docker push 10.200.200.1:5000/laravel-horizon-server:production
docker push 10.200.200.1:5000/percona-xtradb-cluster:production

##  Bring up the stack

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
