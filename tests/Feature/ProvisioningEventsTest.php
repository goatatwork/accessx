<?php

namespace Tests\Feature;

use Storage;
use App\Ont;
use App\Port;
use App\User;
use App\Package;
use App\IpAddress;
use App\OntProfile;
use Tests\TestCase;
use App\OntSoftware;
use App\ServiceLocation;
use App\ProvisioningRecord;
use Illuminate\Support\Facades\Event;
use App\Events\ServiceWasProvisioned;
use App\GoldAccess\Dhcp\ManagementIp;
use App\Events\DeletingProvisioningRecord;
use App\Events\ProvisioningRecordWasUpdated;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProvisioningEventsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        $this->package = factory(Package::class)->create();
    }

    /**
     * Test that the ServiceWasProvisioned event is fired when a provisioning
     * record is created.
     *
     * @return void
     */
    public function test_event_is_fired_when_provisioning_record_is_created()
    {
        Event::fake();

        // Let's get a ProvisioningRecord blueprint up
        $provisioning_record = factory(ProvisioningRecord::class)->make([
            'service_location_id' => null,
            'ont_profile_id' => null,
            'port_id' => null,
            'ip_address_id' => null,
        ]);

        // Other elements needed to form a complete provisioning record
        $service_location = factory(ServiceLocation::class)->create();
        $ont_profile = factory(OntProfile::class)->create();
        $port = factory(Port::class)->create();
        $ip_address = factory(IpAddress::class)->create();

        // This should be submitted from the front end
        $form_data = [
            'service_location_id' => $service_location->id,
            'ont_profile_id' => $ont_profile->id,
            'port_id' => $port->id,
            'ip_address_id' => $ip_address->id,
            'len' => $provisioning_record->len,
            'circuit_id' => $provisioning_record->circuit_id,
            'notes' => $provisioning_record->notes,
            'package_id' => $this->package->id
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
     * Test that the DeletingProvisioningRecord event is fired as a
     * provisioning record is deleted.
     *
     * @return void
     */
    public function test_event_is_fired_when_provisioning_record_is_deleted()
    {
        Event::fake();

        // Let's get a ProvisioningRecord blueprint up
        $provisioning_record = factory(ProvisioningRecord::class)->create();

        $this->assertDatabaseHas('provisioning_records', [
            'id' => $provisioning_record->id,
        ]);

        $response = $this->actingAs($this->user, 'api')->json('DELETE', '/api/provisioning/' . $provisioning_record->id);

        $this->assertDatabaseMissing('provisioning_records', [
            'id' => $provisioning_record->id,
        ]);

        Event::assertDispatched(DeletingProvisioningRecord::class, function($e) use ($provisioning_record) {
            return $e->provisioning_record->id == $provisioning_record->id;
        });
    }

    /**
     * Test that the ProvisioningRecordWasUpdated event is fired as a
     * provisioning record is deleted.
     *
     * @return void
     */
    public function test_event_is_fired_when_provisioning_record_is_updated()
    {
        Event::fake();

        // Let's get a ProvisioningRecord blueprint up
        $provisioning_record = factory(ProvisioningRecord::class)->create();

        $ont_software = $provisioning_record->ont_profile->ont_software;
        $new_profile = factory(OntProfile::class)->create(['ont_software_id' => $ont_software->id]);

        $this->assertDatabaseHas('provisioning_records', [
            'id' => $provisioning_record->id,
        ]);

        $response = $this->actingAs($this->user, 'api')->json('PATCH', '/api/provisioning/' . $provisioning_record->id, ['ont_profile_id' => $new_profile->id]);

        Event::assertDispatched(ProvisioningRecordWasUpdated::class, function($e) use ($provisioning_record) {
            return $e->provisioning_record->id == $provisioning_record->id;
        });
    }
}
