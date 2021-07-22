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
use App\Jobs\RebootOnt;
use App\Jobs\SetRateLimit;
use App\ServiceLocation;
use App\ProvisioningRecord;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use App\Events\ServiceWasProvisioned;
// use App\GoldAccess\Dhcp\ManagementIp;
use App\GoldAccess\Dhcp\Options\ManagementIp;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProvisioningTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        $this->package = factory(Package::class)->create();
    }

    /**
     * @group provisioning
     * @test
     */
    public function api_goatgoat()
    {
        $provisioning_record = factory(ProvisioningRecord::class)->make([
            'service_location_id' => null,
            'ont_profile_id' => null,
            'port_id' => null,
            'ip_address_id' => null,
        ]);

        $service_location = factory(ServiceLocation::class)->create();
        $port = factory(Port::class)->create();
        $ip_address = factory(IpAddress::class)->create();
        $ont = factory(Ont::class)->create();
        $ont_software = factory(OntSoftware::class)->create(['ont_id' => $ont->id]);
        $original_ont_profile = factory(OntProfile::class)->create(['ont_software_id' => $ont_software->id]);
        $package = factory(Package::class)->create();

        $form_data = [
            'service_location_id' => $service_location->id,
            'ont_profile_id' => $original_ont_profile->id,
            'port_id' => $port->id,
            'ip_address_id' => $ip_address->id,
            'len' => $provisioning_record->len,
            'circuit_id' => $provisioning_record->circuit_id,
            'notes' => $provisioning_record->notes,
            'package_id' => $package->id
        ];

        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/provisioning', $form_data);

        $response->assertJson([
            'service_location_id' => $service_location->id,
            'ont_profile_id' => $original_ont_profile->id,
            'port_id' => $port->id,
            'ip_address_id' => $ip_address->id,
            'len' => $provisioning_record->len,
            'circuit_id' => $provisioning_record->circuit_id,
            'notes' => $provisioning_record->notes,
        ]);
    }

    /**
     * @group provisioning
     * @test
     */
    public function api_can_edit_provisioning_record_ont_profile()
    {
        $provisioning_record = factory(ProvisioningRecord::class)->make([
            'service_location_id' => null,
            'ont_profile_id' => null,
            'port_id' => null,
            'ip_address_id' => null,
        ]);

        $service_location = factory(ServiceLocation::class)->create();
        $port = factory(Port::class)->create();
        $ip_address = factory(IpAddress::class)->create();

        $ont = factory(Ont::class)->create();
        $ont_software = factory(OntSoftware::class)->create(['ont_id' => $ont->id]);
        $original_ont_profile = factory(OntProfile::class)->create(['ont_software_id' => $ont_software->id]);
        $new_ont_profile = factory(OntProfile::class)->create(['ont_software_id' => $ont_software->id]);

        $form_data = [
            'service_location_id' => $service_location->id,
            'ont_profile_id' => $original_ont_profile->id,
            'port_id' => $port->id,
            'ip_address_id' => $ip_address->id,
            'len' => $provisioning_record->len,
            'circuit_id' => $provisioning_record->circuit_id,
            'notes' => $provisioning_record->notes,
        ];

        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/provisioning', $form_data);

        $response->assertJson([
            'service_location_id' => $service_location->id,
            'ont_profile_id' => $original_ont_profile->id,
            'port_id' => $port->id,
            'ip_address_id' => $ip_address->id,
            'len' => $provisioning_record->len,
            'circuit_id' => $provisioning_record->circuit_id,
            'notes' => $provisioning_record->notes,
        ]);

        $this->assertDatabaseHas('provisioning_records', [
            'service_location_id' => $service_location->id,
            'ont_profile_id' => $original_ont_profile->id,
            'port_id' => $port->id,
            'ip_address_id' => $ip_address->id,
            'len' => $provisioning_record->len,
            'circuit_id' => $provisioning_record->circuit_id,
            // 'notes' => $provisioning_record->notes,          // has issues with empty vs null
        ]);

        $db_provisioning_record = ProvisioningRecord::whereLen($provisioning_record->len)->first();

        $this->assertFileExists(storage_path('app/services/dnsmasq_testing/dnsmasq.d/'.$db_provisioning_record->port_tag.'.conf'));

        $file = Storage::disk('dhcp_configs_testing')->get('dnsmasq.d/'.$db_provisioning_record->port_tag.'.conf');

        // $this->assertEquals($this->dhcp_file_syntax($db_provisioning_record), $this->file_to_array($file));
        $this->assertEquals($this->dhcp_file_syntax($db_provisioning_record), $file);

        $edit_response = $this->actingAs($this->user, 'api')
            ->json('PATCH', '/api/provisioning/' . $db_provisioning_record->id, ['ont_profile_id' => $new_ont_profile->id]);

        // Make assertions on the data returned from ProvisioningRecordForEditingResource::class
        $edit_response->assertJson([
            'id' => $db_provisioning_record->id,
            'len' => $db_provisioning_record->len,
            'circuit_id' => $db_provisioning_record->circuit_id,
        ]);

        $this->assertDatabaseHas('provisioning_records', [
            'service_location_id' => $service_location->id,
            'ont_profile_id' => $new_ont_profile->id,
            'port_id' => $port->id,
            'ip_address_id' => $ip_address->id,
            'len' => $provisioning_record->len,
            'circuit_id' => $provisioning_record->circuit_id,
            // 'notes' => $provisioning_record->notes,          // has issues with empty vs null
        ]);

        $this->assertFileExists(storage_path('app/services/dnsmasq_testing/dnsmasq.d/'.$db_provisioning_record->port_tag.'.conf'));

        $fresh_db_provisioning_record = ProvisioningRecord::whereLen($provisioning_record->len)->first();
        $updated_file = Storage::disk('dhcp_configs_testing')->get('dnsmasq.d/'.$db_provisioning_record->port_tag.'.conf');

        // $this->assertEquals($this->file_to_array($updated_file), $this->dhcp_file_syntax($fresh_db_provisioning_record));
        $this->assertEquals($this->dhcp_file_syntax($fresh_db_provisioning_record), $updated_file);

    }

    /**
     * @group provisioning
     * @test
     */
    public function test_reboot_job_is_fired_if_pr_update_has_reboot_set_to_true()
    {
        Queue::fake();

        $pr = factory(ProvisioningRecord::class)->create();

        $profile = factory(OntProfile::class)->create(['ont_software_id' => $pr->ont_profile->ont_software->id]);

        $response = $this->actingAs($this->user, 'api')->json('PATCH', '/api/provisioning/' . $pr->id, ['reboot' => true, 'ont_profile_id' => $profile->id]);

        Queue::assertPushed(RebootOnt::class);
    }

    /**
     * @group provisioning
     * @test
     */
    public function test_reboot_job_is_not_fired_if_pr_update_has_reboot_set_to_false()
    {
        Queue::fake();

        $pr = factory(ProvisioningRecord::class)->create();

        $service_location = factory(ServiceLocation::class)->create(['customer_id' => $pr->service_location->customer->id]);

        $response = $this->actingAs($this->user, 'api')->json('PATCH', '/api/provisioning/' . $pr->id, ['reboot' => false, 'service_location_id' => $service_location->id]);

        Queue::assertNotPushed(RebootOnt::class);
    }

    /**
     * @group provisioning
     * @test
     */
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
     * @group provisioning
     * @test
     */
    public function test_api_can_add_provisioning_records_and_dnsmasq_file_is_created()
    {
        $this->withoutExceptionHandling();

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

        $this->assertFileExists(storage_path('app/services/dnsmasq_testing/dnsmasq.d/'.$db_provisioning_record->port_tag.'.conf'));
    }

    /**
     * @group provisioning
     * @test
     */
    public function test_api_can_delete_provisioning_records_and_dnsmasq_file_is_removed()
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

        $this->assertFileExists(storage_path('app/services/dnsmasq_testing/dnsmasq.d/'.$db_provisioning_record->port_tag.'.conf'));

        $delete_response = $this->actingAs($this->user, 'api')->json('DELETE', '/api/provisioning/' . $db_provisioning_record->id);

        $this->assertDatabaseMissing('provisioning_records', [
            'service_location_id' => $service_location->id,
            'ont_profile_id' => $ont_profile->id,
            'port_id' => $port->id,
            'ip_address_id' => $ip_address->id,
            'len' => $provisioning_record->len,
            'circuit_id' => $provisioning_record->circuit_id,
            // 'notes' => $provisioning_record->notes,          // has issues with empty vs null
        ]);

        $this->assertFileNotExists(storage_path('app/services/dnsmasq_testing/dnsmasq.d/'.$db_provisioning_record->port_tag.'.conf'));
    }

    /**
     * The options we assign all new management IPs
     *
     * @param \App\ProvisioningRecord $provisioning_record
     * @return array
     */
    protected function dhcp_file_syntax($provisioning_record)
    {
        return ManagementIp::make($provisioning_record);
        // $name = $provisioning_record->service_location->customer->customer_name;
        // $id = $provisioning_record->service_location->customer->id;

        // $subscriberId = $provisioning_record->port->slot->aggregator->slug . '/' .
        //     $provisioning_record->port->slot->slot_number . '/' .
        //     '1/' .
        //     $provisioning_record->port->port_number;

        // $ip = $provisioning_record->ip_address->address;

        // $netmask = $provisioning_record->ip_address->subnet->subnet_mask;

        // $leasetime = config('goldaccess.settings.dhcp_default_lease_time');

        // $gateway = $provisioning_record->ip_address->subnet->routers;

        // $dns = $provisioning_record->ip_address->subnet->dns_servers;

        // return [
        //     '## Management IP for '.$name.' (ID:'.$id.')',
        //     'dhcp-subscrid=set:"' . $subscriberId . '","' . $subscriberId . '"', // match subscriber id
        //     'dhcp-range=tag:"' . $subscriberId . '",tag:!internet-pool,' . $ip . ',' . $ip . ',' . $netmask . ',' . $leasetime, // the IP
        //     'dhcp-option=tag:"' . $subscriberId . '",tag:!internet-pool,3,' . $gateway, // The gateway
        //     'dhcp-option=tag:"' . $subscriberId . '",tag:!internet-pool,1,' . $netmask, // The netmask
        //     'dhcp-option=tag:"' . $subscriberId . '",tag:!internet-pool,5,' . $dns, // The dns server
        //     'dhcp-option=tag:"' . $subscriberId . '",tag:!internet-pool,67,' . $provisioning_record->dhcp_string,
        //     // 'option-logserver' => 'dhcp-option=tag:"BasementStack/1/3/2",7,10.0.0.4',
        // ];
    }

    /**
     * @group provisioning
     * @group ratelimit
     * @group external-api
     * @test
     * @return void
     */
    // public function test_can_send_get_to_accessr_to_set_rate_limits()
    // {
    //     $url = 'http:/10.0.0.4:3000/api/ports/ratelimit';

    //     $response = $this->json('GET', $url);

    //     $response->assertJson([
    //         'success' => true
    //     ]);
    // }

    /**
     * @group provisioning
     * @group ratelimit
     * @group external-api
     * @test
     * @return void
     */
    // public function test_can_send_patch_to_accessr_to_set_rate_limits()
    // {
    //     $url = 'http:/10.0.0.4:3000/api/ports/ratelimit';

    //     $patch_data = [
    //         'switch_ip' => '192.168.99.1',
    //         'port_name' => 'ethernet1/1/2',
    //         'down_rate' => '10000',
    //         'up_rate'   => '10000'
    //     ];

    //     $response = $this->json('PATCH', $url, $patch_data);

    //     $response->assertJson([
    //         'success' => "On the switch at 192.168.99.1, change port ethernet1/1/2 to have 10000 down and 10000 up."
    //     ]);
    // }

    /**
     * The options we assign all new management IPs
     *
     * @param Storage $file
     * @return array
     */
    protected function file_to_array($file)
    {
        return explode("\n", $file);
    }
}
