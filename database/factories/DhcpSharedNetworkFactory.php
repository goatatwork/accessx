<?php

use Faker\Generator as Faker;

$factory->define(App\DhcpSharedNetwork::class, function (Faker $faker) {
    $name = $faker->words(1, true);
    return [
        'name' => ucfirst($name) . ' Network',
        'management' => false,
        'vlan' => 4
    ];
});
