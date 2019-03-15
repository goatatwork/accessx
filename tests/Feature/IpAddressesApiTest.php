<?php

namespace Tests\Feature;

use App\User;
use App\Subnet;
use App\IpAddress;
use Tests\TestCase;
use App\DhcpSharedNetwork;
use App\ProvisioningRecord;
use App\Events\SubnetWasCreated;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IpAddressesApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }

    /**
     * @group ip-addresses-api
     * @test
     */
    public function test_api_will_deliver_ip_address_for_a_subnet()
    {
        $ip = factory(IpAddress::class)->create();

        $response = $this->actingAs($this->user, 'api')->json('GET', '/api/dhcp/subnets/' . $ip->subnet->id . '/ip_addresses');

        $response->assertJson([
            0 => [
                'address' => $ip->subnet->ip_addresses[0]->address,
            ]
        ]);
    }

    /**
     * @group ip-addresses-api
     * @test
     */
    public function test_api_will_deliver_ip_address_for_a_shared_network()
    {
        $ip = factory(IpAddress::class)->create();

        $response = $this->actingAs($this->user, 'api')
            ->json('GET', '/api/dhcp/dhcp_shared_networks/'.$ip->subnet->dhcp_shared_network->id.'/ip_addresses');

        $response->assertJson([
            0 => [
                'address' => $ip->subnet->ip_addresses[0]->address,
            ]
        ]);
    }

    /**
     * @group ip-addresses-api
     * @test
     */
    public function test_ip_address_knows_if_it_has_provisioning_records()
    {
        $provrec = factory(ProvisioningRecord::class)->create();

        $ip = $provrec->ip_address;

        $this->assertTrue($ip->has_provisioning_records);
    }

    /**
     * @group ip-addresses-api
     * @test
     */
    public function test_ip_address_knows_if_it_does_not_have_provisioning_records()
    {
        $ip = factory(IpAddress::class)->create();

        $this->assertFalse($ip->has_provisioning_records);
    }
}
