FROM php:fpm

MAINTAINER Goat

RUN apt-get update \
&& apt-get -y --no-install-recommends install cron nano \
&& apt-get clean; rm -rf /var/lib/apt/lists/ /tmp/ /var/tmp/ /usr/share/doc/ \
&& export TERM=xterm

COPY laravel.conf /etc/cron.d/laravel.conf

CMD ["/bin/sh", "-c", "cron && tail -f /var/log/cron.log"]
