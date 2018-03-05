<?php

use App\Ont;
use Faker\Generator as Faker;

$factory->define(App\OntSoftware::class, function (Faker $faker) {
    return [
        'ont_id' => function() {
            return factory(Ont::class)->create()->id;
        },
        'version' => $faker->bothify('#.##.##?'),
        'notes' => $faker->randomElement(['', $faker->sentence()]),
    ];
});
