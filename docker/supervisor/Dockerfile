FROM goatatwork/php-fpm7.1:latest

MAINTAINER Goat

RUN apt-get update \
&& apt-get -y --no-install-recommends install supervisor \
&& apt-get clean; rm -rf /var/lib/apt/lists/ /tmp/ /var/tmp/ /usr/share/doc/ \
&& export TERM=xterm

COPY supervisord.conf /etc/supervisord.conf

ENTRYPOINT ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisord.conf"]
