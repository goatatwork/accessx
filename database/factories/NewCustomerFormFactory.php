<?php

use Faker\Generator as Faker;

$factory->define(App\NewCustomerForm::class, function (Faker $faker) {
    return [
        'company_name' => $faker->company(),
        'first_name' => $faker->firstName(),
        'last_name' => $faker->lastName(),
        'address1' => $faker->buildingNumber() . ' ' . $faker->streetName(),
        'address2' => $faker->secondaryAddress(),
        'city' => $faker->city(),
        'state' => $faker->stateAbbr(),
        'zip' => $faker->postCode(),
        'phone1' => $faker->phoneNumber(),
        'phone2' => $faker->phoneNumber(),
        'email' => $faker->safeEmail(),
        'notes' => $faker->sentence(),
        'use_same_address_for_billing' => $faker->boolean(true),
        'billing_contact_name' => $faker->firstName() . ' ' . $faker->lastName(),
        'billing_contact_email' => $faker->safeEmail(),
        'billing_address1' => $faker->buildingNumber() . ' ' . $faker->streetName(),
        'billing_address2' => $faker->secondaryAddress(),
        'billing_city' => $faker->city(),
        'billing_state' => $faker->stateAbbr(),
        'billing_zip' => $faker->postCode(),
        'billing_phone1' => $faker->phoneNumber(),
        'billing_phone2' => $faker->phoneNumber(),
        'billing_notes' => $faker->sentence(),
    ];
});
