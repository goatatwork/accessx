<?php

namespace Tests\Feature;

use App\User;
use App\Subnet;
use Tests\TestCase;
use App\DhcpSharedNetwork;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IpAddressesApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }

    public function test_api_will_deliver_ip_address_for_a_subnet()
    {
        $sn = factory(DhcpSharedNetwork::class)->create();
        $subnet = factory(Subnet::class)->make(['dhcp_shared_network_id' => null]);

        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/dhcp/dhcp_shared_networks/' . $sn->id . '/subnets', $subnet->toArray());

        $response->assertJson([
            'start_ip' => $subnet->start_ip
        ]);

        $this->assertDatabaseHas('subnets', ['start_ip' => $subnet->start_ip]);

        $subnet = Subnet::whereStartIp($subnet->start_ip)->first();
        $this->assertCount(253, $subnet->ip_addresses);

        $response = $this->actingAs($this->user, 'api')->json('GET', '/api/dhcp/subnets/' . $subnet->id . '/ip_addresses');

        $response->assertJson([
            0 => [
                'address' => $subnet->ip_addresses[0]->address,
            ]
        ]);
    }

    public function test_api_will_deliver_ip_address_for_a_shared_network()
    {
        $sn = factory(DhcpSharedNetwork::class)->create();
        $subnet = factory(Subnet::class)->make(['dhcp_shared_network_id' => null]);

        $response = $this->actingAs($this->user, 'api')->json('POST', '/api/dhcp/dhcp_shared_networks/' . $sn->id . '/subnets', $subnet->toArray());

        $response->assertJson([
            'start_ip' => $subnet->start_ip
        ]);

        $this->assertDatabaseHas('subnets', ['start_ip' => $subnet->start_ip]);

        $subnet = Subnet::whereStartIp($subnet->start_ip)->first();
        $this->assertCount(253, $subnet->ip_addresses);

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/dhcp/dhcp_shared_networks/'.$subnet->dhcp_shared_network->id.'/ip_addresses');

        $response->assertJson([
            0 => [
                'address' => $subnet->ip_addresses[0]->address,
            ]
        ]);
    }
}
