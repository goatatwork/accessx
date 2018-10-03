<?php

namespace Tests\Feature;

use App\User;
use App\Customer;
use Tests\TestCase;
use App\BillingRecord;
use App\ServiceLocation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerApiTest extends TestCase
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
    public function test_api_will_fetch_all_customers()
    {
        $customers = factory(Customer::class, 35)->create();

        $response = $this->actingAs($this->user, 'api')->json('GET', '/api/customers');

        $response->assertJson([
            0 => [
                'company_name' => $customers[0]->company_name
            ],
            10 => [
                'company_name' => $customers[10]->company_name
            ],
            20 => [
                'company_name' => $customers[20]->company_name
            ],
            30 => [
                'company_name' => $customers[30]->company_name
            ],
        ]);
    }

    /**
     * @return void
     */
    public function test_api_will_fetch_a_single_customer()
    {
        $customer = factory(Customer::class)->create();

        $response = $this->actingAs($this->user, 'api')->json('GET', '/api/customers/' . $customer->id);

        $response->assertJson([
            'first_name' => $customer->first_name,
            'last_name' => $customer->last_name
        ]);
    }

    /**
     * @return void
     */
    public function test_api_will_update_a_customer()
    {
        $customer = factory(Customer::class)->create();

        $response = $this->actingAs($this->user, 'api')->json('PATCH', '/api/customers/' . $customer->id, ['first_name' => 'Burt', 'last_name' => 'Muston']);

        $response->assertJson([
            'first_name' => 'Burt',
            'last_name' => 'Muston'
        ]);

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'first_name' => 'Burt',
            'last_name' => 'Muston'
        ]);
    }

    /**
     * @return  void
     */
    public function test_api_will_delete_a_customer()
    {
        $customer = factory(Customer::class)->create();

        $this->assertDatabaseHas('customers', [
            'first_name' => $customer->first_name
        ]);

        $response = $this->actingAs($this->user, 'api')->json('DELETE', '/api/customers/' . $customer->id);

        $this->assertDatabaseMissing('customers', [
            'first_name' => $customer->first_name
        ]);
    }

    /**
     * @return  void
     */
    public function test_api_will_delete_a_customer_also_deleting_billing_record_but_leaving_service_location()
    {
        $customer = factory(Customer::class)->create();
        $billing_record = factory(BillingRecord::class)->create(['customer_id' => $customer->id]);
        $service_location = factory(ServiceLocation::class)->create(['customer_id' => $customer->id]);

        $this->assertDatabaseHas('customers', [
            'first_name' => $customer->first_name
        ]);

        $this->assertDatabaseHas('billing_records', [
            'phone1' => $billing_record->phone1
        ]);

        $this->assertDatabaseHas('service_locations', [
            'phone1' => $service_location->phone1
        ]);

        $response = $this->actingAs($this->user, 'api')->json('DELETE', '/api/customers/' . $customer->id);

        $this->assertDatabaseMissing('customers', [
            'first_name' => $customer->first_name
        ]);

        $this->assertDatabaseMissing('billing_records', [
            'phone1' => $billing_record->phone1
        ]);

        $this->assertDatabaseHas('service_locations', [
            'phone1' => $service_location->phone1
        ]);
    }
}
