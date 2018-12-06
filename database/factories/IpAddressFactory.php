<?php

use App\Subnet;
use Faker\Generator as Faker;

$factory->define(App\IpAddress::class, function (Faker $faker) {
    return [
        'subnet_id' => function() {
            return factory(Subnet::class)->create()->id;
        },
        'address' => $faker->ipv4(),
        'vlan' => 4,
        'exclude_from_dhcp' => false,
    ];
});
