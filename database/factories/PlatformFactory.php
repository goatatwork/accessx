<?php

use Faker\Generator as Faker;

$factory->define(App\Platform::class, function (Faker $faker) {
    return [
        'name' => ucfirst($faker->word),
        'number_of_slots' => 4,
        'notes' => $faker->sentence(),
    ];
});
