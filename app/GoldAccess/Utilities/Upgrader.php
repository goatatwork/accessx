<?php

namespace App\GoldAccess\Utilities;

use App\Customer;
use App\BillingRecord;
use App\ServiceLocation;
use App\Access3\Customer as OldCustomer;
use App\Access3\ServiceLocation as OldServiceLocation;
use App\Access3\ProvisioningRecord as OldProvisioningRecord;

class Upgrader
{
    public function go()
    {
        $customers = OldCustomer::with('locations')->get();

        $customers->each(function($customer) {

            $new_customer = Customer::create([
                'company_name' => $customer->company,
                'first_name' => $customer->firstname,
                'last_name' => $customer->lastname,
            ]);

            $billing_record = $new_customer->billing_record()->create([
                'poc_name' => $customer->firstname . ' ' . $customer->lastname,
                'poc_email' => $customer->email,
                'phone1' => $customer->phone1,
                'phone2'=> $customer->phone2,
                'address1' => $customer->billing_address1,
                'address2' => $customer->billing_address2,
                'city' => $customer->billing_city,
                'state' => $customer->billing_state,
                'zip' => $customer->billing_zip,
            ]);

            $customer->locations->each(function($location) use ($customer, $new_customer) {
                $new_customer->service_locations()->create([
                    'name' => $location->name,
                    'poc_name' => $customer->firstname . ' ' . $customer->lastname,
                    'poc_email' => $customer->email,
                    'phone1' => $location->phone,
                    'phone2'=> '',
                    'address1' => $location->address1,
                    'address2' => $location->address2,
                    'city' => $location->city,
                    'state' => $location->state,
                    'zip' => $location->zip,
                ]);
            });

        });
    }
}
