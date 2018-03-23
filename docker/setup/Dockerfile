FROM ubuntu:17.10

MAINTAINER Goat

RUN apt-get update \
    && apt-get install -y locales \
    && locale-gen en_US.UTF-8

ENV LANG en_US.UTF-8
ENV LANGUAGE en_US:en
ENV LC_ALL en_US.UTF-8

RUN apt-get install -y nodejs npm iputils-ping libpng-dev nano \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    && rm -rf /etc/localtime \
    && ln -s /usr/share/zoneinfo/America/Chicago /etc/localtime \
    && dpkg-reconfigure -f noninteractive tzdata

WORKDIR /var/www/html

USER www-data

RUN composer install --no-dev --ignore-platform-reqs \
    && php artisan key:generate \
    && php artisan migrate \
    && php artisan db:seed \
    && php artisan passport:install

CMD ["/bin/bash"]
ENTRYPOINT []
