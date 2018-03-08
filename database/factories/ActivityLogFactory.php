<?php

use Faker\Generator as Faker;

$factory->define(App\ActivityLog::class, function (Faker $faker) {
    return [
        'calling_class' => 'SomeClass',
        'calling_function' => 'someFunction',
        'level' => 'info', // One of emert, alert, crit, err, warning, notice, info, debug
        'message' => 'TESTLOGENTRY: ' . $faker->sentence,
    ];
});
