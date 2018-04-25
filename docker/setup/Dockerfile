FROM php:7.2-fpm

MAINTAINER Goat

WORKDIR /var/www/html

RUN apt-get update \
    && apt-get install -y locales \
    && locale-gen en_US.UTF-8

ENV LANG en_US.UTF-8
ENV LANGUAGE en_US:en
ENV LC_ALL en_US.UTF-8

RUN cp .env.example .env \
    && sed -i -e 's/APP_URL=http:\/\/localhost/APP_URL=http:\/\/10\.200\.200\.1/g' .env \
    && sed -i -e 's/DB_PASSWORD=/DB_PASSWORD=1q2w3e4r/g' .env \
    && sed -i -e 's/"authHost": "http:\/\/accessx\.goat"/"authHost": "http:\/\/10\.200\.200\.1"/g' laravel-echo-server.json \
    && sed -i -e 's/"host": "127\.0\.0\.1"/"host": "redis"/g' laravel-echo-server.json \
    && sed -i -e 's/"http:\/\/nginx\/api\/dnsmasq\/events"/"http:\/\/10\.200\.200\.1\/api\/dnsmasq\/events"/g' storage/app/services/dnsmasq/dhcp-script.sh \
    && touch storage/app/services/dnsmasq/leases/dnsmasq.leases \
    && chown -R www-data.www-data . \
    && chown -R www-data.www-data *

CMD ["/bin/bash"]
