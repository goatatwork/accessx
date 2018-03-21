FROM ubuntu:17.10

MAINTAINER Goat

RUN apt-get update \
    && apt-get install -y nodejs npm \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \

CMD ["/bin/bash"]
ENTRYPOINT []
