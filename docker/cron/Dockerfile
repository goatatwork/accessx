FROM ubuntu:18.04

MAINTAINER Goat

RUN apt-get update
RUN DEBIAN_FRONTEND="noninteractive" apt-get install -y \
    curl unzip zip software-properties-common iputils-ping \
    php7.2 php7.2-cli php7.2-mysql php7.2-gd php7.2-curl php7.2-xml php7.2-bz2 php7.2-mbstring php7.2-zip

RUN apt-get install -y --no-install-recommends cron \
    && apt-get clean; rm -rf /var/lib/apt/lists/ /tmp/ /var/tmp/ /usr/share/doc/

COPY crontab /etc/cron.d/laravel

RUN chmod 0644 /etc/cron.d/laravel

COPY script.sh /script.sh

RUN chmod +x /script.sh

RUN touch /var/log/cron.log

CMD cron && tail -f /var/log/cron.log

