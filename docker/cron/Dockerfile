FROM ubuntu:18.04

MAINTAINER Goat

COPY laravel.conf /etc/crontab

RUN touch /var/log/cron.log

CMD cron && tail -f /var/log/cron.log

