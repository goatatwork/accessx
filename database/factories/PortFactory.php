<?php

use App\Slot;
use App\ModuleType;
use Faker\Generator as Faker;

$factory->define(App\Port::class, function (Faker $faker) {
    $slot = factory(Slot::class)->create();

    return [
        'slot_id' => $slot->id,
        'port_number' => 1,
        'notes' => $faker->sentence()
    ];
});
