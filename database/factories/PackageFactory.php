<?php

use Faker\Generator as Faker;

$factory->define(App\Package::class, function (Faker $faker) {
    return [
        'name' => ucfirst($faker->word),
        'up_rate' => 50000,
        'down_rate' => 50000,
    ];
});
