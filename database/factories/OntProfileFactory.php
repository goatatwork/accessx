<?php

use App\OntSoftware;
use Faker\Generator as Faker;

$factory->define(App\OntProfile::class, function (Faker $faker) {
    return [
        'ont_software_id' => function() {
            return factory(OntSoftware::class)->create()->id;
        },
        'name' => $faker->randomElement([
            '5x5 Package',
            '25x25 Package',
            '50x50 Package',
            '25x5 Package',
            'Debugging Package',
            'No Wifi Package'
        ]),
        'notes' => $faker->sentence(),
    ];
});
