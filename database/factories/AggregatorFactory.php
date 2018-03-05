<?php

use App\Platform;
use Faker\Generator as Faker;

$factory->define(App\Aggregator::class, function (Faker $faker) {
    return [
        'platform_id' => function() {
            return factory(Platform::class)->create()->id;
        },
        'name' => ucfirst($faker->words(1, true)),
        'fqdn' => $faker->domainWord() . '.' . $faker->domainName(),
        'management_ip' => $faker->localIpv4(),
        'monitoring_ip' => $faker->localIpv4(),
        'management_mac' => $faker->macAddress(),
        'notes' => $faker->sentence()
    ];
});
