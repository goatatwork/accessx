<?php

use Faker\Generator as Faker;

$factory->define(App\GaSetting::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'value' => str_slug($faker->words(3, true), '_'),
        'description' => $faker->sentence
    ];
});
