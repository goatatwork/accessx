<?php

namespace Tests\Browser;

use App\Ont;
use App\User;
use App\OntProfile;
use App\OntSoftware;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ShowOntPageTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @group ont-show
     * @test
     */
    public function test_the_ONTs_name_is_displayed()
    {
        $ont = factory(Ont::class)->create();
        $user = factory(User::class)->create();

        $this->browse(function($browser) use ($user, $ont) {
            $browser->loginAs($user)->visit('/onts/'.$ont->id)->assertSee($ont->model_number);
        });
    }

    /**
     * @group ont-show
     * @test
     */
    public function test_it_displays_whether_the_ONT_is_indoor_or_outdoor()
    {
        $outdoor_ont = factory(Ont::class)->create();
        $indoor_ont = factory(Ont::class)->create(['indoor' => true]);
        $user = factory(User::class)->create();

        $this->browse(function($browser) use ($user, $outdoor_ont, $indoor_ont) {
            $browser->loginAs($user)->visit('/onts/'.$outdoor_ont->id)->assertSee('This is an outdoor ONT');
            $browser->loginAs($user)->visit('/onts/'.$indoor_ont->id)->assertSee('This is an indoor ONT');
        });
    }

    /**
     * @group ont-show
     * @test
     */
    public function test_it_displays_whether_the_ONT_has_wifi()
    {
        $wifi_ont = factory(Ont::class)->create(['wifi' => true]);
        $nowifi_ont = factory(Ont::class)->create();
        $user = factory(User::class)->create();

        $this->browse(function($browser) use ($user, $wifi_ont, $nowifi_ont) {
            $browser->loginAs($user)->visit('/onts/'.$wifi_ont->id)->assertSee('This ONT has wifi');
            $browser->loginAs($user)->visit('/onts/'.$nowifi_ont->id)->assertSee('This ONT does not have wifi');
        });
    }

    /**
     * @group ont-show
     * @test
     */
    public function test_it_displays_whether_the_ONT_is_OEM()
    {
        $oem_ont = factory(Ont::class)->create(['oem' => true]);
        $nooem_ont = factory(Ont::class)->create();
        $user = factory(User::class)->create();

        $this->browse(function($browser) use ($user, $oem_ont, $nooem_ont) {
            $browser->loginAs($user)->visit('/onts/'.$oem_ont->id)->assertSee('This ONT is OEM branded');
            $browser->loginAs($user)->visit('/onts/'.$nooem_ont->id)->assertDontSee('This ONT is OEM branded');
        });
    }

    /**
     * @group ont-show
     * @test
     */
    public function test_the_show_ONT_page_displays_software_when_there_is_software()
    {
        $ont = factory(Ont::class)->create();

        $user = factory(User::class)->create();

        $software = factory(OntSoftware::class)->create(['ont_id' => $ont->id]);

        $this->browse(function($browser) use ($ont, $user, $software) {
            $browser->loginAs($user)->visit('/onts/'.$ont->id)->assertSeeLink($software->version);
        });
    }

    /**
     * @group ont-show
     * @test
     */
    public function test_the_show_ONT_page_says_there_is_no_software_when_there_is_no_software()
    {
        $ont = factory(Ont::class)->create();
        $user = factory(User::class)->create();

        $this->browse(function($browser) use ($user, $ont) {
            $browser->loginAs($user)->visit('/onts/'.$ont->id)->assertSee('There is no software here');
        });
    }

    /**
     * @group ont-show
     * @test
     */
    public function test_the_show_ONT_page_does_not_show_the_currently_viewing_software_area_if_none_is_given_in_the_url()
    {
        $ont = factory(Ont::class)->create();

        $user = factory(User::class)->create();

        $software = factory(OntSoftware::class)->create(['ont_id' => $ont->id]);

        $this->browse(function($browser) use ($ont, $user, $software) {
            $browser->loginAs($user)->visit('/onts/'.$ont->id)->assertDontSee('You are currentlying viewing software version ');
        });
    }

    /**
     * @group ont-show
     * @test
     */
    public function test_the_show_ONT_page_shows_the_currently_viewing_software_area_if_one_is_given_in_the_url()
    {
        $ont = factory(Ont::class)->create();

        $user = factory(User::class)->create();

        $software = factory(OntSoftware::class)->create(['ont_id' => $ont->id]);

        $this->browse(function($browser) use ($ont, $user, $software) {
            $browser->loginAs($user)->visit('/onts/'.$ont->id.'?viewsoftware='.$software->id)
                ->assertSee('You are currentlying viewing software version ' . $software->version);
        });
    }

    /**
     * @group ont-show
     * @test
     */
    public function the_page_says_there_are_no_profiles_when_there_are_no_profiles()
    {
        $ont = factory(Ont::class)->create();

        $user = factory(User::class)->create();

        $software = factory(OntSoftware::class)->create(['ont_id' => $ont->id]);

        $this->browse(function($browser) use ($ont, $user, $software) {
            $browser->loginAs($user)->visit('/onts/'.$ont->id.'?viewsoftware='.$software->id)->assertSee('There are no profiles here');
        });
    }

    /**
     * @group ont-show
     * @test
     */
    public function the_page_shows_profiles_when_there_are_profiles()
    {
        $ont = factory(Ont::class)->create();

        $user = factory(User::class)->create();

        $software = factory(OntSoftware::class)->create(['ont_id' => $ont->id]);

        $profile = factory(OntProfile::class)->create(['ont_software_id' => $software->id]);

        $this->browse(function($browser) use ($ont, $user, $software, $profile) {
            $browser->loginAs($user)->visit('/onts/'.$ont->id.'?viewsoftware='.$software->id)->assertSee($profile->notes);
        });
    }
}
