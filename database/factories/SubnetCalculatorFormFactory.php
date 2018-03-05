<?php

use Faker\Generator as Faker;

$factory->define(App\SubnetCalculatorForm::class, function (Faker $faker) {
    $cidr_options = [23, 24, 25, 26, 27, 28, 29, 30];
    return [
        'ip' => $faker->ipv4(),
        'cdir' => $faker->randomElement($cidr_options),
        'gateway_preference' => $faker->randomElement(['top', 'bottom'])
    ];
});
