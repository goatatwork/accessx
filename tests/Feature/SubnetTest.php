<?php

namespace Tests\Feature;

use App\Subnet;
use Tests\TestCase;
use App\DhcpSharedNetwork;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubnetTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @group dhcpbot
     * @group subnet
     * @test
     */
    public function test_a_subnet_knows_if_it_belongs_to_a_management_network()
    {
        $sn = factory(DhcpSharedNetwork::class)->create(['management' => true]);
        $subnet = factory(Subnet::class)->create(['dhcp_shared_network_id' => $sn->id]);

        $this->assertEquals(1, $subnet->is_management);
    }

    /**
     * @group dhcpbot
     * @group subnet
     * @test
     */
    public function test_a_subnet_knows_if_it_does_not_belong_to_a_management_network()
    {
        $sn = factory(DhcpSharedNetwork::class)->create();
        $subnet = factory(Subnet::class)->create(['dhcp_shared_network_id' => $sn->id]);

        $this->assertEquals(0, $subnet->is_management);
    }
}
