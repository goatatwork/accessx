<?php

namespace Tests\Feature;

use Storage;
use App\User;
use App\Subnet;
use Tests\TestCase;
use App\DhcpSharedNetwork;
use App\Events\SubnetWasCreated;
use App\GoldAccess\Dhcp\DhcpBot;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DhcpbotTest extends TestCase
{
    /**
     * @group dhcpbot
     * @test
     */
    public function test_bot_has_a_bio()
    {
        $this->assertEquals('I am Dhcpbot', app('dhcpbot')->bio);
    }

    /**
     * @group dhcpbot
     * @test
     */
    public function test_bot_can_get_the_filename_for_the_dnsmasq_config_file_for_a_subnet()
    {
        $subnet = factory(Subnet::class)->create(['network_address' => '10.1.2.0']);

        $expected_string = 'dnsmasq.d/' . $subnet->dhcp_shared_network->slug . '-10_1_2_0.conf';
        $this->assertEquals($expected_string, app('dhcpbot')->getDhcpFilename($subnet));
    }

    /**
     * @group dhcpbot
     * @test
     */
    public function test_bot_knows_if_a_dnsmasq_config_file_for_a_subnet_does_not_exist_on_the_disk()
    {
        $subnet = factory(Subnet::class)->create(['network_address' => '10.1.2.0']);

        $this->assertFalse(app('dhcpbot')->fileExists($subnet));
    }

    /**
     * @group dhcpbot
     * @test
     */
    public function test_bot_knows_if_a_dnsmasq_config_file_for_a_subnet_does_exist_on_the_disk()
    {
        $sn = factory(DhcpSharedNetwork::class)->create(['name' => 'My Shared Network']);
        $subnet = factory(Subnet::class)->create(['dhcp_shared_network_id' => $sn->id, 'network_address' => '10.1.1.0']);

        Storage::disk('dhcp_configs_test')->put(app('dhcpbot')->getDhcpFilename($subnet), 'Test file contents');

        $this->assertTrue(app('dhcpbot')->fileExists($subnet));

        Storage::disk('dhcp_configs_test')->delete(app('dhcpbot')->getDhcpFilename($subnet));

        $this->assertFalse(app('dhcpbot')->fileExists($subnet));
    }

    /**
     * @group dhcpbot
     * @test
     */
    public function test_bot_can_write_per_subnet_dnsmasq_configs_to_disk()
    {
        $sn = factory(DhcpSharedNetwork::class)->create(['name' => 'My Shared Network']);
        $subnet = factory(Subnet::class)->create(['dhcp_shared_network_id' => $sn->id, 'network_address' => '10.1.1.0']);

        $this->assertFalse(app('dhcpbot')->fileExists($subnet));

        $this->assertTrue(app('dhcpbot')->writeFile($subnet));

        $this->assertTrue(app('dhcpbot')->fileExists($subnet));

        Storage::disk('dhcp_configs_test')->delete(app('dhcpbot')->getDhcpFilename($subnet));

        $this->assertFalse(app('dhcpbot')->fileExists($subnet));
    }

    /**
     * @group dhcpbot
     * @test
     */
    public function test_bot_can_write_and_delete_per_subnet_dnsmasq_configs_to_disk()
    {
        $sn = factory(DhcpSharedNetwork::class)->create(['name' => 'My Shared Network']);
        $subnet = factory(Subnet::class)->create(['dhcp_shared_network_id' => $sn->id, 'network_address' => '10.1.1.0']);

        $this->assertFalse(app('dhcpbot')->fileExists($subnet));

        $this->assertTrue(app('dhcpbot')->writeFile($subnet));

        $this->assertTrue(app('dhcpbot')->fileExists($subnet));

        $this->assertTrue(app('dhcpbot')->deleteFile($subnet));

        $this->assertFalse(app('dhcpbot')->fileExists($subnet));
    }

    /**
     * @group dhcpbot
     * @test
     */
    public function test_bot_can_generate_dhcp_range_string_for_dnmasq_file()
    {
        $subnet = factory(Subnet::class)->create(['network_address' => '10.1.2.0']);

        $expected_string = $subnet->start_ip.','.$subnet->end_ip.','.$subnet->subnet_mask;

        $this->assertEquals($expected_string, app('dhcpbot')->rangeFor($subnet));
    }

    /**
     * @group dhcpbot
     * @test
     */
    public function test_bot_can_generate_the_file_content_for_a_dnsmasq_config_file_for_a_subnet()
    {
        $subnet = factory(Subnet::class)->create(['network_address' => '10.1.2.0']);

        $expected_string = '# DHCP Range For ' . $subnet->dhcp_shared_network->name . ' On VLAN 4' . "\n" .
            'dhcp-range='.$subnet->start_ip.','.$subnet->end_ip.','.$subnet->subnet_mask.',1h';

        $this->assertEquals($expected_string, app('dhcpbot')->fileContent($subnet));
    }

    /**
     * @group dhcpbot
     * @test
     */
    public function test_the_SubnetWasCreated_event_is_fired_when_the_api_creates_a_new_subnet()
    {
        Event::fake();

        $user = factory(User::class)->create();
        $sn = factory(DhcpSharedNetwork::class)->create();
        $subnet = factory(Subnet::class)->make(['dhcp_shared_network_id' => null]);

        $response = $this->actingAs($user, 'api')
            ->json('POST', '/api/dhcp/dhcp_shared_networks/' . $sn->id . '/subnets', $subnet->toArray());

        Event::assertDispatched(SubnetWasCreated::class);
    }

    /**
     * @group dhcpbot
     * @test
     */
    public function test_the_CreateDhcpFileForSubnet_listener_fires_and_creates_file_for_new_NONmanagement_subnet_when_the_api_creates_a_new_subnet()
    {
        $user = factory(User::class)->create();
        $sn = factory(DhcpSharedNetwork::class)->create();
        $subnet = factory(Subnet::class)->make(['dhcp_shared_network_id' => null]);

        $response = $this->actingAs($user, 'api')
            ->json('POST', '/api/dhcp/dhcp_shared_networks/' . $sn->id . '/subnets', $subnet->toArray());

        $this->assertTrue(app('dhcpbot')->fileExists($sn->subnets()->first()));

        app('dhcpbot')->deleteFile($sn->subnets()->first());

        $this->assertFalse(app('dhcpbot')->fileExists($sn->subnets()->first()));
    }

    /**
     * @group dhcpbot
     * @test
     */
    public function test_the_CreateDhcpFileForSubnet_listener_fires_and_does_not_create_file_for_new_management_subnet_when_the_api_creates_a_new_subnet()
    {
        $user = factory(User::class)->create();
        $sn = factory(DhcpSharedNetwork::class)->create(['management' => true]);
        $subnet = factory(Subnet::class)->make(['dhcp_shared_network_id' => null]);

        $response = $this->actingAs($user, 'api')
            ->json('POST', '/api/dhcp/dhcp_shared_networks/' . $sn->id . '/subnets', $subnet->toArray());

        $this->assertFalse(app('dhcpbot')->fileExists($sn->subnets()->first()));

    }
}
