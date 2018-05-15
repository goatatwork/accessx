<?php

namespace Tests\Feature;

use App\User;
use App\Customer;
use Tests\TestCase;
use App\ServiceLocation;
use App\ProvisioningRecord;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }

    /**
     * Customer should know if it does not have provisioning records
     * @return void
     */
    public function test_that_customer_knows_if_it_does_not_have_provisioning_records()
    {
        $service_location = factory(ServiceLocation::class)->create();

        $customer = $service_location->customer;

        $this->assertFalse($customer->has_provisioning_records);
        $this->assertEquals(0, $customer->number_of_provisioning_records);
    }

    /**
     * Customer should know if it has provisioning records if it has one
     * provisioning record
     * @return void
     */
    public function test_that_customer_knows_if_it_has_provisioning_records_if_it_has_one_provisioning_record()
    {
        $provrec = factory(ProvisioningRecord::class)->create();

        $customer = $provrec->service_location->customer;

        $this->assertTrue($customer->has_provisioning_records);
        $this->assertEquals(1, $customer->number_of_provisioning_records);
    }

    /**
     * Customer should know if it has provisioning records if it has more than
     * one provisioning record
     * @return void
     */
    public function test_that_customer_knows_if_it_has_provisioning_records_if_it_has_more_than_one_provisioning_record()
    {
        $service_location = factory(ServiceLocation::class)->create();

        $provrecs = factory(ProvisioningRecord::class, 5)->create(['service_location_id' => $service_location->id]);

        $customer = $service_location->customer;

        $this->assertTrue($customer->has_provisioning_records);
        $this->assertEquals(5, $customer->number_of_provisioning_records);
    }

    /**
     * Customer should know if it does not have service locations
     * @return void
     */
    public function test_that_customer_knows_if_it_does_not_have_service_locations()
    {
        $customer = factory(Customer::class)->create();

        $this->assertFalse($customer->has_service_locations);
        $this->assertEquals(0, $customer->number_of_service_locations);
    }

    /**
     * Customer should know if it has service locations if it has one
     * provisioning record
     * @return void
     */
    public function test_that_customer_knows_if_it_has_one_service_location()
    {
        $service_location = factory(ServiceLocation::class)->create();

        $customer = $service_location->customer;

        $this->assertTrue($customer->has_service_locations);
        $this->assertEquals(1, $customer->number_of_service_locations);
    }

    /**
     * Customer should know if it has more than one service_location
     * @return void
     */
    public function test_that_customer_knows_if_it_has_more_than_one_service_location()
    {
        $customer = factory(Customer::class)->create();

        $service_locations = factory(ServiceLocation::class, 5)->create(['customer_id' => $customer->id]);

        $this->assertTrue($customer->has_service_locations);
        $this->assertEquals(5, $customer->number_of_service_locations);
    }
}
