<?php

use Faker\Generator as Faker;

$factory->define(App\Subnet::class, function (Faker $faker) {
    $name = $faker->words(1, true);
    $subnet = implode(['10',rand(1,254),rand(1,254)], '.');
    return [
        'dhcp_shared_network_id' => function() {
            return factory(App\DhcpSharedNetwork::class)->create()->id;
        },
        'comment' => $name,
        'network_address' => $subnet . '.0',
        'subnet_mask' => '255.255.255.0',
        'cidr' => '24',
        'start_ip' => $subnet . '.1',
        'end_ip' => $subnet . '.253',
        'routers' => $subnet . '.254',
        'broadcast_address' => $subnet . '.255',
        'dns_servers' => '8.8.8.8',
        'default_lease_time' => '3600',
        'max_lease_time' => '3605',
    ];
});
