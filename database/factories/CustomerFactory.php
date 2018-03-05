<?php

use Faker\Generator as Faker;

$factory->define(App\Customer::class, function (Faker $faker) {
    return [
        'company_name' => $faker->company(),
        'first_name' => $faker->firstName(),
        'last_name' => $faker->lastName(),
        'notes' => $faker->sentence
    ];
});
