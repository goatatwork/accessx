FROM ubuntu:18.04

MAINTAINER Goat

ENV LANG en_US.UTF-8
ENV LANGUAGE en_US:en
ENV LC_ALL en_US.UTF-8

RUN apt-get update \
    && apt-get install -y tzdata locales \
    && locale-gen en_US.UTF-8

RUN apt-get install -y curl unzip zip software-properties-common php7.2 php7.2-cli php7.2-mysql php7.2-gd php7.2-curl \
        php7.2-xml php7.2-bz2 php7.2-mbstring php7.2-zip iputils-ping

RUN apt-get install -y --no-install-recommends cron \
    && apt-get clean; rm -rf /var/lib/apt/lists/ /tmp/ /var/tmp/ /usr/share/doc/

COPY laravel /etc/cron.d/laravel

RUN chmod 0644 /etc/cron.d/laravel

RUN crontab /etc/cron.d/laravel

RUN touch /var/log/cron.log

# CMD ["/bin/sh", "-c", "cron -f && tail -f /var/log/cron.log"]
CMD ["cron", "-f"]

