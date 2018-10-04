<?php

use App\Aggregator;
use Faker\Generator as Faker;

$factory->define(App\Slot::class, function (Faker $faker) {
    $aggregator = factory(Aggregator::class)->create();
    return [
        'aggregator_id' => $aggregator->id,
        'module_type_id' => null,
        'slot_number' => 1,
        'notes' => $faker->sentence()
    ];
});
