<?php

use App\OntSoftware;
use Faker\Generator as Faker;

$factory->define(App\OntProfile::class, function (Faker $faker) {
    return [
        'ont_software_id' => function() {
            return factory(OntSoftware::class)->create()->id;
        },
        'name' => 'Unlimited',
        'notes' => $faker->sentence(),
    ];
});
