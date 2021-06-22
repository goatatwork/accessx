<?php

namespace Tests\Feature;

use App\User;
use App\Customer;
use Tests\TestCase;
use App\BillingRecord;
use App\ServiceLocation;
use Illuminate\Support\Arr;
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
    // public function test_api_will_fetch_all_customers()
    // {
    //     $customers = factory(Customer::class, 35)->create();

    //     $response = $this->actingAs($this->user, 'api')->json('GET', '/api/customers?sort_key=customer_name&sort_order=asc');

    //     // $customers_db = Customer::orderBy('company_name', 'asc')->orderBy('last_name', 'asc')->paginate(50);

    //     // $customers_all = Customer::all()->sortBy('customer_name', SORT_NATURAL|SORT_FLAG_CASE);
    //     $customers_all = $customers->sortBy('customer_name', SORT_NATURAL|SORT_FLAG_CASE);

    //     $response->assertJson(['data'=>$customers_all->toArray()]);
    // }

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
            'id' => $customer->id,
            'first_name' => $customer->first_name
        ]);
    }

    /**
     * This happens because of how the migrations are written. Billing records
     * are onDelete('cascade');
     *
     * @return  void
     */
    public function test_api_will_delete_a_customer_also_deleting_billing_record_but_leaving_service_location()
    {
        $customer = factory(Customer::class)->create();
        $billing_record = factory(BillingRecord::class)->create(['customer_id' => $customer->id]);
        $service_location = factory(ServiceLocation::class)->create(['customer_id' => $customer->id]);

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'first_name' => $customer->first_name
        ]);

        $this->assertDatabaseHas('billing_records', [
            'customer_id' => $customer->id,
            'phone1' => $billing_record->phone1
        ]);

        $this->assertDatabaseHas('service_locations', [
            'customer_id' => $customer->id,
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
