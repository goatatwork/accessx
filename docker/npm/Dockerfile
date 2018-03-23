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

WORKDIR /var/www/html

CMD ["/bin/bash"]
ENTRYPOINT []
