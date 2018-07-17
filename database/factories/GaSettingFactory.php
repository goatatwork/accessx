<?php

use Faker\Generator as Faker;

$factory->define(App\GaSetting::class, function (Faker $faker) {
    return [
        'name' => str_slug($faker->words(3, true), '_'),
        'value' => $faker->word,
        'description' => $faker->sentence
    ];
});
