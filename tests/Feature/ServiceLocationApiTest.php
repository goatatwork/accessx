<?php

namespace Tests\Feature;

use App\User;
use App\Customer;
use Tests\TestCase;
use App\ServiceLocation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ServiceLocationApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }

    /**
     * @return void
     */
    public function test_api_can_update_a_service_location()
    {
        $service_location = factory(ServiceLocation::class)->create();

        $response = $this->actingAs($this->user, 'api')->json('PATCH', '/api/service_locations/' . $service_location->id, ['phone1' => '111-111-1111']);

        $response->assertJson([
            'phone1' => '111-111-1111'
        ]);
    }

    /**
     * @return void
     */
    public function test_api_can_create_service_locations()
    {
        $customer = factory(Customer::class)->create();
        $service_location = factory(ServiceLocation::class)->make(['customer_id' => null]);

        $url = '/api/customers/' . $customer->id . '/service_locations';

        $response = $this->actingAs($this->user, 'api')->json('POST', $url, $service_location->toArray());

        $response->assertJson([
            'poc_name' => $service_location->poc_name
        ]);
    }

    /**
     * @return void
     */
    public function test_api_can_delete_service_location_records()
    {
        $service_location = factory(ServiceLocation::class)->create();

        $response = $this->actingAs($this->user, 'api')->json('DELETE', '/api/service_locations/' . $service_location->id);

        $this->assertDatabaseMissing('service_locations', [
            'id' => $service_location->id
        ]);
    }
}
