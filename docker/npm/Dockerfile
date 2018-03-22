FROM ubuntu:17.10

MAINTAINER Goat

RUN apt-get update \
    && apt-get install -y nodejs npm iputils-ping libpng-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \

WORKDIR /var/www/html

CMD ["/bin/bash"]
ENTRYPOINT []
