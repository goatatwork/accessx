<?php

namespace Tests\Feature;

use App\User;
use App\Port;
use App\IpAddress;
use Tests\TestCase;
use App\OntProfile;
use App\ServiceLocation;
use App\ProvisioningRecord;
use App\GoldAccess\Dhcp\ManagementIp;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ManagementIpClassTest extends TestCase
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
    public function test_management_ip_dhcp_config_is_created_with_provisioning_record()
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

        $management_ip = new ManagementIp($db_provisioning_record);

        $this->assertTrue($management_ip->check());

    }



}
