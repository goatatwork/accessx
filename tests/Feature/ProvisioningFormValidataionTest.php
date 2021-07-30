<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use App\ProvisioningRecord;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProvisioningFormValidataionTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        $this->pr = factory(ProvisioningRecord::class)->make();
    }

    /**
     * @return  void
     */
    public function test_api_will_fail_pr_validation_if_missing_ont_profile_id()
    {
        $this->assertDatabaseHas('ont_profiles', [
            'id' => $this->pr->ont_profile->id,
        ]);

        $this->pr->ont_profile_id = null;

        $this->postNew()->assertJson([
            'errors' => [
                'ont_profile_id' => [
                    'The ont profile id field is required.'
                ]
            ]
        ]);
    }

    /**
     * @return  void
     */
    public function test_api_will_fail_pr_validation_if_missing_service_location_id()
    {
        $this->assertDatabaseHas('service_locations', [
            'id' => $this->pr->service_location->id,
        ]);

        $this->pr->service_location_id = null;

        $this->postNew()->assertJson([
            'errors' => [
                'service_location_id' => [
                    'The service location id field is required.'
                ]
            ]
        ]);
    }

    /**
     * @return  void
     */
    public function test_api_will_fail_pr_validation_if_missing_port_id()
    {
        $this->assertDatabaseHas('ports', [
            'id' => $this->pr->port->id,
        ]);

        $this->pr->port_id = null;

        $this->postNew()->assertJson([
            'errors' => [
                'port_id' => [
                    'The port id field is required.'
                ]
            ]
        ]);
    }

    /**
     * @return  void
     */
    public function test_api_will_fail_pr_validation_if_missing_ip_address_id()
    {
        $this->pr->ip_address_id = null;

        $this->postNew()->assertJson([
            'errors' => [
                'ip_address_id' => [
                    'The ip address id field is required.'
                ]
            ]
        ]);
    }

    /**
     * @return  void
     */
    public function test_api_will_fail_pr_validation_if_missing_package_id()
    {
        $this->postNew()->assertJson([
            'errors' => [
                'package_id' => [
                    'The package id field is required.'
                ]
            ]
        ]);
    }

    /**
     * 'POST' $this->pr to the API in whatever state it is currently in
     * @return
     */
    public function postNew()
    {
        return $this->actingAs($this->user, 'api')->json('POST', '/api/provisioning', $this->pr->toArray());
    }
}
