<?php

namespace Tests\Unit;

use App\Customer;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerTest extends TestCase
{
    /**
     * @return void
     */
    public function test_a_residential_customer_has_a_name()
    {
        $customer = factory(Customer::class)->create(['company_name' => 'Goat, Inc.']);
        $this->assertEquals('Goat, Inc.', $customer->customer_name);
    }

    /**
     * @return void
     */
    public function test_a_business_customer_has_a_name()
    {
        $customer = factory(Customer::class)->create(['company_name' => '']);
        $this->assertEquals($customer->first_name . ' ' . $customer->last_name, $customer->customer_name);
    }
}
