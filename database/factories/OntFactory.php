<?php

use Faker\Generator as Faker;

$factory->define(App\Ont::class, function (Faker $faker) {
    return [
        'model_number' => '2427A',
        'manufacturer' => 'Zhone',
        'wifi' => false,
        'indoor' => false,
        'number_of_pots_lines' => 2,
        'number_of_ethernet_ports' => 4,
        'oem' => false,
        'notes' => $faker->sentence,
    ];
});

$factory->state(App\Ont::class, 'oem', [
    'oem' => true,
]);

$factory->state(App\Ont::class, 'nonoem', [
    'oem' => false,
]);
