#!/bin/bash
# 20180406 Goat
# Dnsmasq Webhook for GoldAccess
# Use this with the dnsmasq config paramater
#
# dhcp-script=/usr/local/bin/dhcp-script.sh

# generate_post_data()
# {
#     echo "\"{ACTION: '$1',HOSTMAC:'$2',IP:'$3',HOSTNAME:'$4',DNSMASQ_DOMAIN:'$DNSMASQ_DOMAIN',DNSMASQ_SUPPLIED_HOSTNAME:'$DNSMASQ_SUPPLIED_HOSTNAME',DNSMASQ_TIME_REMAINING:'$DNSMASQ_TIME_REMAINING',DNSMASQ_OLD_HOSTNAME:'$DNSMASQ_OLD_HOSTNAME',DNSMASQ_INTERFACE:'$DNSMASQ_INTERFACE',DNSMASQ_RELAY_ADDRESS:'$DNSMASQ_RELAY_ADDRESS',DNSMASQ_TAGS:'$DNSMASQ_TAGS',DNSMASQ_LOG_DHCP:'$DNSMASQ_LOG_DHCP',DNSMASQ_CLIENT_ID:'$DNSMASQ_CLIENT_ID',DNSMASQ_CIRCUIT_ID:'$DNSMASQ_CIRCUIT_ID',DNSMASQ_SUBSCRIBER_ID:'$DNSMASQ_SUBSCRIBER_ID',DNSMASQ_REMOTE_ID:'$DNSMASQ_REMOTE_ID',DNSMASQ_VENDOR_CLASS:'$DNSMASQ_VENDOR_CLASS',DNSMASQ_REQUESTED_OPTIONS:'$DNSMASQ_REQUESTED_OPTIONS'}\""
# }

# curl -i \
# -H "Accept: application/json" \
# -H "Content-Type:application/json" \
# -H "X-CSRF-TOKEN:LGri4hy2pGlx9wVpVvVqTHRwwavZVn2vYu2PS4a2" \
# -X POST --data "$(generate_post_data)" "http://nginx/api/dnsmasq/events"
