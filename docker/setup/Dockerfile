FROM goldaccess/php-fpm:production

MAINTAINER Goat

ENV LANG en_US.UTF-8
ENV LANGUAGE en_US:en
ENV LC_ALL en_US.UTF-8

WORKDIR /var/www/html

RUN cd /var/www/html \
    && cp .env.example .env \
    && sed -i -e 's/APP_URL=http:\/\/localhost/APP_URL=http:\/\/10\.200\.200\.1/g' .env \
    && sed -i -e 's/DB_PASSWORD=/DB_PASSWORD=1q2w3e4r/g' .env \
    && sed -i -e 's/"authHost": "http:\/\/accessx\.goat"/"authHost": "http:\/\/10\.200\.200\.1"/g' laravel-echo-server.json \
    && sed -i -e 's/"host": "127\.0\.0\.1"/"host": "redis"/g' laravel-echo-server.json \
    && sed -i -e 's/"http:\/\/nginx\/api\/dnsmasq\/events"/"http:\/\/10\.200\.200\.1\/api\/dnsmasq\/events"/g' storage/app/services/dnsmasq/dhcp-script.sh \
    && touch storage/app/services/dnsmasq/leases/dnsmasq.leases \
    && chown -R www-data.www-data . \
    && composer install --no-dev --ignore-platform-reqs \
    && php artisan key:generate \
    && php artisan migrate \
    && php artisan db:seed \
    && php artisan passport:install \
    && php artisan storage:link \
    && chown -R www-data.www-data . \
    && chown -R www-data.www-data * \
    && mkdir /var/www/.config \
    && chown -R www-data.www-data /var/www/.config

CMD ["/bin/bash"]
