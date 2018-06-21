FROM php:fpm

MAINTAINER Goat

RUN apt-get update \
&& apt-get -y --no-install-recommends install supervisor iputils-ping nano \
&& apt-get clean; rm -rf /var/lib/apt/lists/ /tmp/ /var/tmp/ /usr/share/doc/ \
&& export TERM=xterm

COPY supervisord.conf /etc/supervisord.conf

ENTRYPOINT ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisord.conf"]
