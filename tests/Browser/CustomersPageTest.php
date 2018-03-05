<?php

namespace Tests\Browser;

use App\User;
use App\Customer;
use App\BillingRecord;
use Tests\DuskTestCase;
use App\ServiceLocation;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CustomersPageTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Verify the customers page shows customers
     * @group customers-index
     * @return void
     */
    public function test_customers_page_shows_customers()
    {
        $user = factory(User::class)->create();
        $customers = factory(Customer::class, 5)->create();
        $customers->each(function ($customer, $key) {
            $billing_record = factory(BillingRecord::class)->make();
            $service_location = factory(ServiceLocation::class)->make();
            $customer->billing_record()->save($billing_record);
            $customer->service_locations()->save($service_location);
        });


        $this->browse(function($browser) use ($user, $customers) {
            $browser->loginAs($user)->visit('/customers');

            $customers->each(function($customer, $index) use ($browser) {
                $browser->assertSee($customer->customer_name);
            });
        });
    }
}
