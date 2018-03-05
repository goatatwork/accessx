<?php

use Faker\Generator as Faker;

$factory->define(App\DnsmasqLog::class, function (Faker $faker) {
    return [
        'event' => json_encode([
            'action' => 'old',
            'hostmac' => '52:54:00:xx:xx:xx',
            'ip' => '192.168.168.1',
            'hostname' => '',
            'DNSMASQ_DOMAIN' => '',
            'DNSMASQ_SUPPLIED_HOSTNAME' => '',
            'DNSMASQ_TIME_REMAINING' => '',
            'DNSMASQ_OLD_HOSTNAME' => '',
            'DNSMASQ_INTERFACE' => '',
            'DNSMASQ_RELAY_ADDRESS' => '',
            'DNSMASQ_TAGS' => '',
            'DNSMASQ_LOG_DHCP' => '',
            'DNSMASQ_CLIENT_ID' => '',
            'DNSMASQ_CIRCUIT_ID' => '',
            'DNSMASQ_SUBSCRIBER_ID' => '',
            'DNSMASQ_REMOTE_ID' => '',
            'DNSMASQ_VENDOR_CLASS' => '',
            'DNSMASQ_REQUESTED_OPTIONS' => '',
        ])
    ];
});
