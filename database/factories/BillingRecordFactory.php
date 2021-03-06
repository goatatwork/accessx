<?php

use App\Customer;
use Faker\Generator as Faker;

$factory->define(App\BillingRecord::class, function (Faker $faker) {
    return [
        'customer_id' => function() {
            return factory(Customer::class)->create()->id;
        },
        'poc_name' => $faker->firstName() . ' ' . $faker->lastName(),
        'poc_email' => $faker->safeEmail(),
        'phone1' => $faker->phoneNumber(),
        'phone2' => $faker->randomElement(['',$faker->phoneNumber()]),
        'address1' => $faker->buildingNumber() . ' ' . $faker->streetName(),
        'address2' => $faker->randomElement(['',$faker->secondaryAddress()]),
        'city' => $faker->city(),
        'state' => $faker->stateAbbr(),
        'zip' => $faker->postCode(),
        'notes' => $faker->randomElement(['',$faker->sentence()])
    ];
});
