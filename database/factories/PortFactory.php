<?php

use App\Slot;
use App\ModuleType;
use Faker\Generator as Faker;

$factory->define(App\Port::class, function (Faker $faker) {
    $slot = factory(Slot::class)->create();
    $module_type = factory(ModuleType::class)->create([
        'platform_id' => $slot->aggregator->platform->id
    ]);
    return [
        'slot_id' => $slot->id,
        'port_number' => $faker->numberBetween(1, $module_type->number_of_ports),
        'notes' => $faker->randomElement(['', $faker->sentence()])
    ];
});
