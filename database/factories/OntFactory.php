<?php

use Faker\Generator as Faker;

$factory->define(App\Ont::class, function (Faker $faker) {
    return [
        'model_number' => $faker->bothify('CPE-####?'),
        'manufacturer' => 'Zhone',
        'wifi' => true,
        'indoor' => true,
        'number_of_pots_lines' => 2,
        'number_of_ethernet_ports' => 4,
        'oem' => false
    ];
});
