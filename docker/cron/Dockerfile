FROM ubuntu:18.04

MAINTAINER Goat

RUN apt-get update && apt-get install -y cron

COPY laravel.conf /etc/crontab

RUN chmod 0644 /etc/crontab

RUN touch /var/log/cron.log

CMD cron && tail -f /var/log/cron.log

