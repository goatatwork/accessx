<?php

use App\Port;
use App\IpAddress;
use App\OntProfile;
use App\ServiceLocation;
use Faker\Generator as Faker;

$factory->define(App\ProvisioningRecord::class, function (Faker $faker) {
    return [
        'service_location_id' => function() {
            return factory(ServiceLocation::class)->create()->id;
        },
        'ont_profile_id' => function() {
            return factory(OntProfile::class)->create()->id;
        },
        'port_id' => function() {
            return factory(Port::class)->create()->id;
        },
        'ip_address_id' => function() {
            return factory(IpAddress::class)->create()->id;
        },
        'len' => $faker->bothify('LEN##-##-#?-#?'),
        'circuit_id' => $faker->bothify('CIR##-##-#?-#?'),
        'notes' => $faker->sentence,
    ];
});
