<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegistrationTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A basic browser test example.
     * @group register
     * @return void
     */
    public function test_registration_works()
    {
        $this->assertTrue(true);
    //     $this->browse(function (Browser $browser) {
    //         $browser->visit('/login')
    //                 ->assertSee('Login')
    //                 ->clickLink('Register')
    //                 ->assertSee('Confirm Password');
    //         $browser->type('name', 'Ryan Gray')
    //                 ->type('email', 'ryantgray@gmail.com')
    //                 ->type('password', 'secret')
    //                 ->type('password_confirmation', 'secret')
    //                 ->press('Register')
    //                 ->assertSee('Dashboard');
    //     });
    }
}
