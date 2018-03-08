<?php

namespace Tests\Feature;

use App\Port;
use App\User;
use App\IpAddress;
use App\OntProfile;
use Tests\TestCase;
use App\ServiceLocation;
use App\ProvisioningRecord;
use Illuminate\Support\Facades\Event;
use App\Events\ServiceWasProvisioned;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProvisioningTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }

    public function test_api_can_add_provisioning_records_and_event_is_fired()
    {
        Event::fake();

        $provisioning_record = factory(ProvisioningRecord::class)->make([
            'service_location_id' => null,
            'ont_profile_id' => null,
            'port_id' => null,
            'ip_address_id' => null,
        ]);

        $service_location = factory(ServiceLocation::class)->create();
        $ont_profile = factory(OntProfile::class)->create();
        $port = factory(Port::class)->create();
        $ip_address = factory(IpAddress::class)->create();

        $form_data = [
            'service_location_id' => $service_location->id,
            'ont_profile_id' => $ont_profile->id,
            'port_id' => $port->id,
            'ip_address_id' => $ip_address->id,
            'len' => $provisioning_record->len,
            'circuit_id' => $provisioning_record->circuit_id,
            'notes' => $provisioning_record->notes,
        ];

        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/provisioning', $form_data);

        $response->assertJson([
            'service_location_id' => $service_location->id,
            'ont_profile_id' => $ont_profile->id,
            'port_id' => $port->id,
            'ip_address_id' => $ip_address->id,
            'len' => $provisioning_record->len,
            'circuit_id' => $provisioning_record->circuit_id,
            'notes' => $provisioning_record->notes,
        ]);

        $this->assertDatabaseHas('provisioning_records', [
            'service_location_id' => $service_location->id,
            'ont_profile_id' => $ont_profile->id,
            'port_id' => $port->id,
            'ip_address_id' => $ip_address->id,
            'len' => $provisioning_record->len,
            'circuit_id' => $provisioning_record->circuit_id,
            // 'notes' => $provisioning_record->notes,          // has issues with empty vs null
        ]);

        Event::assertDispatched(ServiceWasProvisioned::class, function($e) use ($form_data) {
            return $e->provisioning_record->port_id == $form_data['port_id'];
        });
    }

    /**
     * @return void
     */
    public function test_api_can_add_provisioning_records_and_dnsmasq_file_is_created()
    {
        $provisioning_record = factory(ProvisioningRecord::class)->make([
            'service_location_id' => null,
            'ont_profile_id' => null,
            'port_id' => null,
            'ip_address_id' => null,
        ]);

        $service_location = factory(ServiceLocation::class)->create();
        $ont_profile = factory(OntProfile::class)->create();
        $port = factory(Port::class)->create();
        $ip_address = factory(IpAddress::class)->create();

        $form_data = [
            'service_location_id' => $service_location->id,
            'ont_profile_id' => $ont_profile->id,
            'port_id' => $port->id,
            'ip_address_id' => $ip_address->id,
            'len' => $provisioning_record->len,
            'circuit_id' => $provisioning_record->circuit_id,
            'notes' => $provisioning_record->notes,
        ];

        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/provisioning', $form_data);

        $response->assertJson([
            'service_location_id' => $service_location->id,
            'ont_profile_id' => $ont_profile->id,
            'port_id' => $port->id,
            'ip_address_id' => $ip_address->id,
            'len' => $provisioning_record->len,
            'circuit_id' => $provisioning_record->circuit_id,
            'notes' => $provisioning_record->notes,
        ]);

        $this->assertDatabaseHas('provisioning_records', [
            'service_location_id' => $service_location->id,
            'ont_profile_id' => $ont_profile->id,
            'port_id' => $port->id,
            'ip_address_id' => $ip_address->id,
            'len' => $provisioning_record->len,
            'circuit_id' => $provisioning_record->circuit_id,
            // 'notes' => $provisioning_record->notes,          // has issues with empty vs null
        ]);

        $db_provisioning_record = ProvisioningRecord::whereLen($provisioning_record->len)->first();

        $this->assertFileExists(storage_path('app/services/dnsmasq_test/dnsmasq.d/'.$db_provisioning_record->port_tag_unique.'.conf'));
    }

}
