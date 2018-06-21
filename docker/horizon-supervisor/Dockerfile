FROM php:7.2

MAINTAINER Goat

RUN apt-get update \
    && apt-get install -y curl unzip zip software-properties-common php7.2-cli php7.2-mysql php7.2-gd php7.2-curl \
    php7.2-xml php7.2-bz2 php7.2-mbstring php7.2-zip iputils-ping nano

RUN apt-get -y --no-install-recommends install supervisor \
    && apt-get clean; rm -rf /var/lib/apt/lists/ /tmp/ /var/tmp/ /usr/share/doc/

COPY supervisord.conf /etc/supervisord.conf

ENTRYPOINT ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisord.conf"]
