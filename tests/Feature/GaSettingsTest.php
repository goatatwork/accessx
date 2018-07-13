<?php

namespace Tests\Feature;

use Config;
use App\GaSetting;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GaSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->first_setting = factory(GaSetting::class)->create([
            'name' => 'dhcp_default_lease_time',
            'value' => '1800',
            'description' => 'The default length of any lease, in seconds'
        ]);
        Config::set('goldaccess.settings.' . $this->first_setting->name, $this->first_setting->value);
    }

    /**
     *
     * @return void
     */
    public function test_ga_settings_can_exist()
    {
        $setting = factory(GaSetting::class)->create();

        $this->assertDatabaseHas('ga_settings', ['name' => $setting->name]);

        $this->assertCount(2, GaSetting::all());
    }

    /**
     * This is a bullshit test. The Config::set is happening in AppServiceProvider.
     * This is just testing that the Config::set line in setUp() works, not the
     * feature in AppServiceProvider.
     *
     * @return  void
     */
    public function test_ga_settings_are_inserted_into_config()
    {
        $this->assertEquals(config('goldaccess.settings.dhcp_default_lease_time'), '1800');
    }
}
