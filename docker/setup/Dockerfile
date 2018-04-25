FROM goldaccess/php-fpm:production

MAINTAINER Goat

ENV LANG en_US.UTF-8
ENV LANGUAGE en_US:en
ENV LC_ALL en_US.UTF-8

WORKDIR /var/www/html

RUN git clone https://github.com/goatatwork/accessx.git

WORKDIR /var/www/html/accessx

    RUN cp .env.example .env \
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

RUN apt-get update \
    && apt-get install -y locales \
    && locale-gen en_US.UTF-8

RUN apt-get install -y curl zip unzip git software-properties-common tzdata iputils-ping nano \
    && add-apt-repository -y ppa:ondrej/php \
    && apt-get update \
    && apt-get install -y php7.1-fpm php7.1-cli php7.1-mcrypt php7.1-gd php7.1-mysql \
       php7.1-pgsql php7.1-imap php-memcached php7.1-mbstring php7.1-xml php7.1-curl \
       php7.1-sqlite3 php7.1-xdebug \
    && php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer \
    && mkdir /run/php \
    && apt-get remove -y --purge software-properties-common \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    && rm -rf /etc/localtime \
    && ln -s /usr/share/zoneinfo/America/Chicago /etc/localtime \
    && dpkg-reconfigure -f noninteractive tzdata

COPY php-fpm.conf /etc/php/7.1/fpm/php-fpm.conf
COPY php.ini /etc/php/7.1/fpm/php.ini
ADD www.conf /etc/php/7.1/fpm/pool.d/www.conf

CMD ["/bin/bash"]

