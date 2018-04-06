FROM ubuntu:16.04

RUN apt-get update \
    && apt-get install -y dnsmasq dhcpdump dhcping software-properties-common iputils-ping nano tzdata \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    && rm -rf /etc/localtime \
    && ln -s /usr/share/zoneinfo/America/Chicago /etc/localtime \
    && dpkg-reconfigure -f noninteractive tzdata

EXPOSE 53 53/udp
EXPOSE 67 67/udp
EXPOSE 69 69/udp

ADD dnsmasq.conf /etc/dnsmasq/dnsmasq.conf

CMD ["dnsmasq", "-k", "--log-facility=-", "--conf-file=/etc/dnsmasq/dnsmasq.conf"]
ENTRYPOINT []

# The last time this was built was March 19, 2018 and it was tagged as goatatwork/dnsmasq-ubuntu:0.3 and :latest
