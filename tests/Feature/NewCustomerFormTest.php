<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\NewCustomerForm;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewCustomerFormTest extends TestCase
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
    public function test_newcustomerform_api_can_create_customer_with_matching_billing_and_service_locations()
    {
        $form_data = factory(NewCustomerForm::class)->make(['use_same_address_for_billing' => true]);

        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/customers', $form_data->toArray());

        $response->assertJson([
            'first_name' => $form_data->first_name,
            'service_locations' => [
                0 => [
                    'phone1' => $form_data->phone1,
                ]
            ],
            'billing_record' => [
                'phone1' => $form_data->phone1,
            ]
        ]);
    }

    /**
     * @return void
     */
    public function test_newcustomerform_api_can_create_customer_with_non_matching_billing_and_service_locations()
    {
        $form_data = factory(NewCustomerForm::class)->make(['use_same_address_for_billing' => false]);

        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/customers', $form_data->toArray());

        $response->assertJson([
            'first_name' => $form_data->first_name,
            'service_locations' => [
                0 => [
                    'phone1' => $form_data->phone1,
                ]
            ],
            'billing_record' => [
                'phone1' => $form_data->billing_phone1,
            ]
        ]);
    }

    /**
     * @return void
     */
    public function test_newcustomerform_can_create_customer_with_matching_billing_and_service_locations()
    {
        $form_data = factory(NewCustomerForm::class)->make(['use_same_address_for_billing' => true]);

        $response = $this->actingAs($this->user, 'web')->post('/customers', $form_data->toArray());

        $response->assertStatus(302);

        $this->assertDatabaseHas('customers', ['first_name' => $form_data->first_name]);
        $this->assertDatabaseHas('billing_records', ['poc_email' => $form_data->email]);
        $this->assertDatabaseHas('service_locations', ['poc_email' => $form_data->email]);
    }

    /**
     * @return void
     */
    public function test_newcustomerform_can_create_customer_with_non_matching_billing_and_service_locations()
    {
        $form_data = factory(NewCustomerForm::class)->make(['use_same_address_for_billing' => false]);

        $response = $this->actingAs($this->user, 'web')->post('/customers', $form_data->toArray());

        $response->assertStatus(302);

        $this->assertDatabaseHas('customers', ['first_name' => $form_data->first_name]);
        $this->assertDatabaseHas('billing_records', ['poc_email' => $form_data->billing_contact_email, 'address1' => $form_data->billing_address1]);
        $this->assertDatabaseHas('service_locations', ['poc_email' => $form_data->email, 'address1' => $form_data->address1]);
    }
}
