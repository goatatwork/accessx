<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use App\NewCustomerForm;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateCustomerFormTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @group customer-form-matching
     * @return  void
     */
    public function test_form_can_create_a_customer_with_matching_billing_and_service_addresses()
    {
        $user = factory(User::class)->create();
        $form = factory(NewCustomerForm::class)->make();

        $this->browse(function(Browser $browser) use ($user, $form) {
            $browser->loginAs($user)->visit('/customers/create')->assertSee('Create A Customer');

            $browser->type('company_name', '')
                    ->type('first_name', $form->first_name)
                    ->type('last_name', $form->last_name)
                    ->type('address1', $form->address1)
                    ->type('address2', $form->address2)
                    ->type('city', $form->city)
                    ->select('state', $form->state)
                    ->type('zip', $form->zip)
                    ->type('phone1', $form->phone1)
                    ->type('phone2', $form->phone2)
                    ->type('email', $form->email)
                    ->type('notes', $form->notes)
                    ->check('use_same_address_for_billing')
                    ->type('billing_contact_name', $form->billing_contact_name)
                    ->type('billing_contact_email', $form->billing_contact_email)
                    ->type('billing_address1', $form->billing_address1)
                    ->type('billing_address2', $form->billing_address2)
                    ->type('billing_city', $form->billing_city)
                    ->select('billing_state', $form->billing_state)
                    ->type('billing_zip', $form->billing_zip)
                    ->type('billing_phone1', $form->billing_phone1)
                    ->type('billing_phone2', $form->billing_phone2)
                    ->type('billing_notes', $form->billing_notes)
                    ->press('Create Customer')->assertSee('Total Customers');
        });

        $this->assertDatabaseHas('customers', ['first_name' => $form->first_name]);
        $this->assertDatabaseHas('service_locations', ['poc_name' => $form->first_name . ' ' . $form->last_name]);
        $this->assertDatabaseHas('billing_records', ['poc_name' => $form->first_name . ' ' . $form->last_name]);
    }

    /**
     * @group customer-form-notmatching
     * @return  void
     */
    public function test_form_can_create_a_customer_with_nonmatching_billing_and_service_addresses()
    {
        $user = factory(User::class)->create();
        $form = factory(NewCustomerForm::class)->make();

        $this->browse(function(Browser $browser) use ($user, $form) {
            $browser->loginAs($user)->visit('/customers/create')->assertSee('Create A Customer');

            $browser->type('company_name', '')
                    ->type('first_name', $form->first_name)
                    ->type('last_name', $form->last_name)
                    ->type('address1', $form->address1)
                    ->type('address2', $form->address2)
                    ->type('city', $form->city)
                    ->select('state', $form->state)
                    ->type('zip', $form->zip)
                    ->type('phone1', $form->phone1)
                    ->type('phone2', $form->phone2)
                    ->type('email', $form->email)
                    ->type('notes', $form->notes)
                    ->uncheck('use_same_address_for_billing')
                    ->type('billing_contact_name', $form->billing_contact_name)
                    ->type('billing_contact_email', $form->billing_contact_email)
                    ->type('billing_address1', $form->billing_address1)
                    ->type('billing_address2', $form->billing_address2)
                    ->type('billing_city', $form->billing_city)
                    ->select('billing_state', $form->billing_state)
                    ->type('billing_zip', $form->billing_zip)
                    ->type('billing_phone1', $form->billing_phone1)
                    ->type('billing_phone2', $form->billing_phone2)
                    ->type('billing_notes', $form->billing_notes)
                    ->press('Create Customer')->assertSee('Total Customers');
        });

        $this->assertDatabaseHas('customers', ['first_name' => $form->first_name]);
        $this->assertDatabaseHas('service_locations', ['poc_name' => $form->first_name . ' ' . $form->last_name, 'address1' => $form->address1]);
        $this->assertDatabaseHas('billing_records', ['poc_name' => $form->billing_contact_name, 'address1' => $form->billing_address1]);
    }

    /**
     * @group customer-form-checkbox
     * @return  void
     */
    public function test_form_checkbox_to_change_display_of_billing_fields_works()
    {
        $user = factory(User::class)->create();

        $this->browse(function(Browser $browser) use ($user) {
            $browser->loginAs($user)->visit('/customers/create')->assertSee('Create A Customer')->assertDontSee('Billing Contact Name');
            $browser->uncheck('use_same_address_for_billing')->assertSee('Billing Contact Name');
            $browser->check('use_same_address_for_billing')->assertDontSee('Billing Contact Name');
        });
    }
}
