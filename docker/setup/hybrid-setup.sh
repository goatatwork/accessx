#!/bin/bash
# 20180323 Goat
# This is intended to be run from the docker HOST. Not within the container

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
docker push 10.200.200.1:5000/proxysql:production
docker push 10.200.200.1:5000/percona-xtradb-cluster:production

docker stack deploy -c docker-compose-swarm.yml ga_support

cp .env.example .env
sed -i -e 's/APP_URL=http:\/\/localhost/APP_URL=http:\/\/10\.200\.200\.1/g' .env
sed -i -e 's/DB_PASSWORD=/DB_PASSWORD=1q2w3e4r/g' .env
sed -i -e 's/"authHost": "http:\/\/accessx\.goat"/"authHost": "http:\/\/10\.200\.200\.1"/g' laravel-echo-server.json
sed -i -e 's/"host": "127\.0\.0\.1"/"host": "redis"/g' laravel-echo-server.json
sed -i -e 's/"http:\/\/nginx\/api\/dnsmasq\/events"/"http:\/\/10\.200\.200\.1\/api\/dnsmasq\/events"/g' storage/app/services/dnsmasq/dhcp-script.sh
touch storage/app/services/dnsmasq/leases/dnsmasq.leases
chown -R www-data.www-data .

# Now let's start the containers
./develop.sh -f docker-compose-hybrid.yml up -d --build

sleep 3

# Prep the application
docker exec -it -u www-data accessx_php_1 composer install --no-dev --ignore-platform-reqs
docker exec -it -u www-data accessx_php_1 php artisan key:generate
docker exec -it -u www-data accessx_php_1 php artisan migrate
docker exec -it -u www-data accessx_php_1 php artisan db:seed
docker exec -it -u www-data accessx_php_1 php artisan passport:install
docker exec -it -u www-data accessx_php_1 php artisan storage:link
docker exec -it -u root accessx_php_1 chown -R www-data.www-data .
docker exec -it -u root accessx_php_1 chown -R www-data.www-data *
# So that the www-data user can use tinker
docker exec -it -u root accessx_php_1 mkdir /var/www/.config
docker exec -it -u root accessx_php_1 chown -R www-data.www-data /var/www/.config

# Restart a few things now that everything is configured
docker restart accessx_horizon-supervisor_1

echo "All finished. Make sure to place one of your API keys into storage/app/services/dnsmasq/dhcp-script.sh"

# docker run -d --net=host -v $(pwd)/storage/app/services/dnsmasq:/etc/dnsmasq -v $(pwd)/public/storage/media:/tftpboot -p 67:67 -p 67:67/udp -p 69:69 -p 69:69/udp --rm --name dnsmasq_server goatatwork/dnsmasq-ubuntu:latest
