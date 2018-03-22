FROM ubuntu:17.10

USER root

RUN apt-get update \
    && apt-get install -y locales \
    && locale-gen en_US.UTF-8

ENV LANG en_US.UTF-8
ENV LANGUAGE en_US:en
ENV LC_ALL en_US.UTF-8

RUN apt-get update \
    && apt-get install -y nodejs npm libpng-dev curl zip unzip software-properties-common tzdata iputils-ping \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    && rm -rf /etc/localtime \
    && ln -s /usr/share/zoneinfo/America/Chicago /etc/localtime \
    && dpkg-reconfigure -f noninteractive tzdata

WORKDIR /var/www/html

RUN npm install -g laravel-echo-server

CMD ["laravel-echo-server", "start"]
