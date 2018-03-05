<?php

use Faker\Generator as Faker;

$factory->define(App\Platform::class, function (Faker $faker) {
    return [
        'name' => ucfirst($faker->word),
        'number_of_slots' => $faker->randomElement([1,4,6,8,12,16]),
        'notes' => $faker->randomElement(['', $faker->sentence()])
    ];
});
