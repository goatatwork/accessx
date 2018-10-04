<?php

use App\Platform;
use Faker\Generator as Faker;

$factory->define(App\ModuleType::class, function (Faker $faker) {
    return [
        'platform_id' => function() {
            return factory(Platform::class)->create()->id;
        },
        'name' => ucfirst($faker->word),
        'number_of_ports' => 24,
        'notes' => $faker->sentence()
    ];
});
