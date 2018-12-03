<?php

namespace Tests\Browser;

use App\Ont;
use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class OntFormsTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @return void
     */
    public function test_the_ONT_create_page_has_an_OEM_checkbox_field_that_is_not_checked_by_default()
    {
        $user = factory(User::class)->create();
        $ont = factory(Ont::class)->states(['oem'])->make();

        $this->browse(function (Browser $browser) use ($user, $ont) {
            $browser->loginAs($user)->visit('/onts/create')
                    ->assertSee('Wifi')
                    ->assertSee('Indoor')
                    ->assertSee('OEM')
                    ->assertNotChecked('oem');
        });
    }

    /**
     * @return void
     */
    public function test_the_ONT_edit_page_has_an_OEM_checkbox_field_that_is_checked_if_the_ont_is_oem()
    {
        $user = factory(User::class)->create();
        $ont = factory(Ont::class)->states(['oem'])->create();

        $this->browse(function (Browser $browser) use ($user, $ont) {
            $browser->loginAs($user)->visit('/onts/'.$ont->id.'/edit')
                    ->assertSee('Wifi')
                    ->assertSee('Indoor')
                    ->assertSee('OEM')
                    ->assertChecked('oem');
        });
    }

    /**
     * @return void
     */
    public function test_the_ONT_create_page_creates_a_non_oem_ont()
    {
        $user = factory(User::class)->create();
        $ont = factory(Ont::class)->states(['nonoem'])->make();

        $this->browse(function (Browser $browser) use ($user, $ont) {
            $browser->loginAs($user)->visit('/onts/create')
                    ->assertSee('Wifi')
                    ->assertSee('Indoor')
                    ->assertSee('OEM')
                    ->assertNotChecked('oem');

            $browser->type('model_number', $ont->model_number)
                ->type('manufacturer', $ont->manufacturer)
                ->type('number_of_pots_lines', $ont->number_of_pots_lines)
                ->type('number_of_ethernet_ports', $ont->number_of_ethernet_ports)
                ->type('notes', $ont->notes)
                ->check('indoor')
                ->check('wifi');


            $browser->click('@create-ont-submit-button');

        });

        $this->assertDatabaseHas('onts', [
            'model_number' => $ont->model_number,
            'manufacturer' => $ont->manufacturer,
            'wifi' => true,
            'indoor' => true,
            'number_of_pots_lines' => $ont->number_of_pots_lines,
            'number_of_ethernet_ports' => $ont->number_of_ethernet_ports,
            'oem' => false,
            'notes' => $ont->notes,
        ]);
    }

    /**
     * @return void
     */
    public function test_the_ONT_create_page_creates_an_oem_ont()
    {
        $user = factory(User::class)->create();
        $ont = factory(Ont::class)->states(['oem'])->make();

        $this->browse(function (Browser $browser) use ($user, $ont) {
            $browser->loginAs($user)->visit('/onts/create')
                    ->assertSee('Wifi')
                    ->assertSee('Indoor')
                    ->assertSee('OEM')
                    ->assertNotChecked('oem');

            $browser->type('model_number', $ont->model_number)
                ->type('manufacturer', $ont->manufacturer)
                ->type('number_of_pots_lines', $ont->number_of_pots_lines)
                ->type('number_of_ethernet_ports', $ont->number_of_ethernet_ports)
                ->type('notes', $ont->notes)
                ->check('indoor')
                ->check('wifi')
                ->check('oem');

            $browser->click('@create-ont-submit-button');

        });

        $this->assertDatabaseHas('onts', [
            'model_number' => $ont->model_number,
            'manufacturer' => $ont->manufacturer,
            'wifi' => true,
            'indoor' => true,
            'number_of_pots_lines' => $ont->number_of_pots_lines,
            'number_of_ethernet_ports' => $ont->number_of_ethernet_ports,
            'oem' => true,
            'notes' => $ont->notes,
        ]);
    }

    /**
     * @return void
     */
    public function test_the_ONT_create_page_creates_a_wifi_ont()
    {
        $user = factory(User::class)->create();
        $ont = factory(Ont::class)->make();

        $this->browse(function (Browser $browser) use ($user, $ont) {
            $browser->loginAs($user)->visit('/onts/create')
                    ->assertSee('Wifi')
                    ->assertSee('Indoor')
                    ->assertSee('OEM')
                    ->assertNotChecked('oem');

            $browser->type('model_number', $ont->model_number)
                ->type('manufacturer', $ont->manufacturer)
                ->type('number_of_pots_lines', $ont->number_of_pots_lines)
                ->type('number_of_ethernet_ports', $ont->number_of_ethernet_ports)
                ->type('notes', $ont->notes)
                ->check('wifi');

            $browser->click('@create-ont-submit-button');

        });

        $this->assertDatabaseHas('onts', [
            'model_number' => $ont->model_number,
            'manufacturer' => $ont->manufacturer,
            'wifi' => true,
            'notes' => $ont->notes,
        ]);
    }

    /**
     * @return void
     */
    public function test_the_ONT_create_page_creates_a_non_wifi_ont()
    {
        $user = factory(User::class)->create();
        $ont = factory(Ont::class)->make();

        $this->browse(function (Browser $browser) use ($user, $ont) {
            $browser->loginAs($user)->visit('/onts/create')
                    ->assertSee('Wifi')
                    ->assertSee('Indoor')
                    ->assertSee('OEM')
                    ->assertNotChecked('oem');

            $browser->type('model_number', $ont->model_number)
                ->type('manufacturer', $ont->manufacturer)
                ->type('number_of_pots_lines', $ont->number_of_pots_lines)
                ->type('number_of_ethernet_ports', $ont->number_of_ethernet_ports)
                ->type('notes', $ont->notes)
                ->uncheck('wifi');

            $browser->click('@create-ont-submit-button');

        });

        $this->assertDatabaseHas('onts', [
            'model_number' => $ont->model_number,
            'manufacturer' => $ont->manufacturer,
            'wifi' => false,
            'notes' => $ont->notes,
        ]);
    }

    /**
     * @return void
     */
    public function test_the_ONT_create_page_creates_an_indoor_ont()
    {
        $user = factory(User::class)->create();
        $ont = factory(Ont::class)->make();

        $this->browse(function (Browser $browser) use ($user, $ont) {
            $browser->loginAs($user)->visit('/onts/create')
                    ->assertSee('Wifi')
                    ->assertSee('Indoor')
                    ->assertSee('OEM')
                    ->assertNotChecked('oem');

            $browser->type('model_number', $ont->model_number)
                ->type('manufacturer', $ont->manufacturer)
                ->type('number_of_pots_lines', $ont->number_of_pots_lines)
                ->type('number_of_ethernet_ports', $ont->number_of_ethernet_ports)
                ->type('notes', $ont->notes)
                ->check('indoor');

            $browser->click('@create-ont-submit-button');

        });

        $this->assertDatabaseHas('onts', [
            'model_number' => $ont->model_number,
            'manufacturer' => $ont->manufacturer,
            'indoor' => true,
            'notes' => $ont->notes,
        ]);
    }

    /**
     * @return void
     */
    public function test_the_ONT_create_page_creates_an_outdoor_ont()
    {
        $user = factory(User::class)->create();
        $ont = factory(Ont::class)->make();

        $this->browse(function (Browser $browser) use ($user, $ont) {
            $browser->loginAs($user)->visit('/onts/create')
                    ->assertSee('Wifi')
                    ->assertSee('Indoor')
                    ->assertSee('OEM')
                    ->assertNotChecked('oem');

            $browser->type('model_number', $ont->model_number)
                ->type('manufacturer', $ont->manufacturer)
                ->type('number_of_pots_lines', $ont->number_of_pots_lines)
                ->type('number_of_ethernet_ports', $ont->number_of_ethernet_ports)
                ->type('notes', $ont->notes)
                ->uncheck('indoor');

            $browser->click('@create-ont-submit-button');

        });

        $this->assertDatabaseHas('onts', [
            'model_number' => $ont->model_number,
            'manufacturer' => $ont->manufacturer,
            'indoor' => false,
            'notes' => $ont->notes,
        ]);
    }

    /**
     * @group ont_edit_page
     * @return void
     */
    public function test_the_ONT_edit_page_has_an_OEM_checkbox_field_that_is_not_checked_if_the_ont_is_not_oem()
    {
        $user = factory(User::class)->create();
        $ont = factory(Ont::class)->states(['nonoem'])->create();

        $this->browse(function (Browser $browser) use ($user, $ont) {
            $browser->loginAs($user)->visit('/onts/'.$ont->id.'/edit')
                    ->assertSee('Wifi')
                    ->assertSee('Indoor')
                    ->assertSee('OEM')
                    ->assertNotChecked('oem');
        });
    }

    /**
     * @group ont_edit_page
     * @return void
     */
    public function test_the_ONT_edit_page_correctly_displays_the_current_state_of_the_ont()
    {
        $user = factory(User::class)->create();
        $ont = factory(Ont::class)->create();

        $this->browse(function(Browser $browser) use ($user, $ont) {
            $browser->loginAs($user)->visit('/onts/'.$ont->id.'/edit')
                ->assertSee('Edit ' . $ont->model_number);

            $browser
                ->assertInputValue('model_number', $ont->model_number)
                ->assertInputValue('manufacturer', $ont->manufacturer)
                ->assertNotChecked('indoor')
                ->assertNotChecked('wifi')
                ->assertNotChecked('oem')
                ->assertInputValue('notes', $ont->notes);
        });
    }

    /**
     * @group ont_edit_page
     * @return void
     */
    public function test_the_ONT_edit_page_correctly_displays_the_current_state_of_the_oem_onts()
    {
        $user = factory(User::class)->create();
        $ont = factory(Ont::class)->states(['oem'])->create();

        $this->browse(function(Browser $browser) use ($user, $ont) {
            $browser->loginAs($user)->visit('/onts/'.$ont->id.'/edit')
                ->assertSee('Edit ' . $ont->model_number);

            $browser
                ->assertInputValue('model_number', $ont->model_number)
                ->assertInputValue('manufacturer', $ont->manufacturer)
                ->assertNotChecked('indoor')
                ->assertNotChecked('wifi')
                ->assertChecked('oem')
                ->assertInputValue('notes', $ont->notes);
        });
    }

    /**
     * @group ont_edit_page
     * @return void
     */
    public function test_the_ONT_edit_page_can_edit_add_wifi_to_an_ont()
    {
        $user = factory(User::class)->create();
        $ont = factory(Ont::class)->create();

        $this->browse(function(Browser $browser) use ($user, $ont) {
            $browser->loginAs($user)->visit('/onts/'.$ont->id.'/edit')
                ->assertSee('Edit ' . $ont->model_number);

            $browser
                ->assertInputValue('model_number', $ont->model_number)
                ->assertInputValue('manufacturer', $ont->manufacturer)
                ->assertNotChecked('indoor')
                ->assertNotChecked('wifi')
                ->assertNotChecked('oem');

            $browser->check('wifi');

            $browser->click('@edit-ont-submit-button');

        });

        $this->assertDatabaseHas('onts', [
            'model_number' => $ont->model_number,
            'manufacturer' => $ont->manufacturer,
            'notes' => $ont->notes,
            'wifi' => true,
            'indoor' => false,
            'oem' => false
        ]);
    }

    /**
     * @group ont_edit_page
     * @return void
     */
    public function test_the_ONT_edit_page_can_edit_add_indoor_to_an_ont()
    {
        $user = factory(User::class)->create();
        $ont = factory(Ont::class)->create();

        $this->browse(function(Browser $browser) use ($user, $ont) {
            $browser->loginAs($user)->visit('/onts/'.$ont->id.'/edit')
                ->assertSee('Edit ' . $ont->model_number);

            $browser
                ->assertInputValue('model_number', $ont->model_number)
                ->assertInputValue('manufacturer', $ont->manufacturer)
                ->assertNotChecked('indoor')
                ->assertNotChecked('wifi')
                ->assertNotChecked('oem');

            $browser->check('indoor');

            $browser->click('@edit-ont-submit-button');

        });

        $this->assertDatabaseHas('onts', [
            'model_number' => $ont->model_number,
            'manufacturer' => $ont->manufacturer,
            'notes' => $ont->notes,
            'wifi' => false,
            'indoor' => true,
            'oem' => false
        ]);
    }

    /**
     * @group ont_edit_page
     * @return void
     */
    public function test_the_ONT_edit_page_can_edit_add_oem_to_an_ont()
    {
        $user = factory(User::class)->create();
        $ont = factory(Ont::class)->create();

        $this->browse(function(Browser $browser) use ($user, $ont) {
            $browser->loginAs($user)->visit('/onts/'.$ont->id.'/edit')
                ->assertSee('Edit ' . $ont->model_number);

            $browser
                ->assertInputValue('model_number', $ont->model_number)
                ->assertInputValue('manufacturer', $ont->manufacturer)
                ->assertNotChecked('indoor')
                ->assertNotChecked('wifi')
                ->assertNotChecked('oem');

            $browser->check('oem');

            $browser->click('@edit-ont-submit-button');

        });

        $this->assertDatabaseHas('onts', [
            'model_number' => $ont->model_number,
            'manufacturer' => $ont->manufacturer,
            'notes' => $ont->notes,
            'wifi' => false,
            'indoor' => false,
            'oem' => true
        ]);
    }
}
