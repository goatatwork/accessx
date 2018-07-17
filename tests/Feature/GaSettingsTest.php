<?php

namespace Tests\Feature;

use Config;
use App\User;
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
        $this->user = factory(User::class)->create();
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

    public function test_api_can_retrieve_settings()
    {
        $settings = factory(GaSetting::class, 25)->create();

        $response = $this->actingAs($this->user, 'api')->json('GET', '/api/settings');

        // These are one digit off because $this->first_setting
        $response->assertJson([
            1 => ['name' => $settings[0]->name, 'value' => $settings[0]->value, 'description' => $settings[0]->description],
            2 => ['name' => $settings[1]->name, 'value' => $settings[1]->value, 'description' => $settings[1]->description],
        ]);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $settings);
    }

    public function test_api_can_update_settings()
    {
        $this->assertEquals($this->first_setting->value, '1800');

        $response = $this->actingAs($this->user, 'api')->json('PATCH', '/api/settings/' . $this->first_setting->name, [
            'name' => $this->first_setting->name,
            'value' => '1313'
        ]);

        $response->assertJson([
            'name' => $this->first_setting->name,
            'value' => '1313',
            'description' => $this->first_setting->description
        ]);

        $this->assertDatabaseHas('ga_settings', [
            'name' => $this->first_setting->name,
            'value' => '1313',
            'description' => $this->first_setting->description
        ]);
    }

    public function test_api_fails_validation_if_value_is_not_present() {
        $response = $this->actingAs($this->user, 'api')->json('PATCH', '/api/settings/' . $this->first_setting->name, [
            'name' => $this->first_setting->name,
        ]);

        $response->assertJson([
            'errors' => [
                'value' => [
                    $this->first_setting->name . ' requires a value.'
                ]
            ]
        ]);
    }

    public function test_api_fails_validation_if_lease_time_has_no_suffix_and_is_less_than_120() {
        $response = $this->actingAs($this->user, 'api')->json('PATCH', '/api/settings/' . $this->first_setting->name, [
            'name' => $this->first_setting->name,
            'value' => '115'
        ]);

        $response->assertJson([
            'errors' => [
                'value' => [
                    'The minimum number of seconds for a dhcp lease is 120.'
                ]
            ]
        ]);
    }

    public function test_api_fails_validation_if_lease_time_is_given_in_minutes_and_it_is_under_2() {
        $response = $this->actingAs($this->user, 'api')->json('PATCH', '/api/settings/' . $this->first_setting->name, [
            'name' => $this->first_setting->name,
            'value' => '1m'
        ]);

        $response->assertJson([
            'errors' => [
                'value' => [
                    'The minimum number of minutes for a dhcp lease is 2.'
                ]
            ]
        ]);
    }

    public function test_api_fails_validation_if_lease_time_is_given_in_hours_and_it_is_under_1() {
        $response = $this->actingAs($this->user, 'api')->json('PATCH', '/api/settings/' . $this->first_setting->name, [
            'name' => $this->first_setting->name,
            'value' => '.5h'
        ]);

        $response->assertJson([
            'errors' => [
                'value' => [
                    'The minimum number of hours for a dhcp lease is 1.'
                ]
            ]
        ]);
    }
}
