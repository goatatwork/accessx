<?php

use Faker\Generator as Faker;

$factory->define(App\Ont::class, function (Faker $faker) {
    return [
        'model_number' => $faker->bothify('CPE-####?'),
        'manufacturer' => 'Zhone',
        'wifi' => $faker->boolean($chanceOfGettingTrue = 50),
        'indoor' => $faker->boolean($chanceOfGettingTrue = 50),
        'number_of_pots_lines' => 2,
        'number_of_ethernet_ports' => 4,
    ];
});
