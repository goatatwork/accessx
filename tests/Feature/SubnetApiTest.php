<?php

namespace Tests\Feature;

use App\User;
use App\Subnet;
use Tests\TestCase;
use App\DhcpSharedNetwork;
use App\ProvisioningRecord;
use App\Events\SubnetWasCreated;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubnetApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }

    /**
     * @test
     */
    public function test_the_subnetwascreated_event_is_fired_when_the_api_creates_a_new_subnet()
    {
        Event::fake();

        $sn = factory(DhcpSharedNetwork::class)->create();
        $subnet = factory(Subnet::class)->make(['dhcp_shared_network_id' => null]);

        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/dhcp/dhcp_shared_networks/' . $sn->id . '/subnets', $subnet->toArray());

        Event::assertDispatched(SubnetWasCreated::class);
    }

    /**
     * @test
     */
    public function test_the_new_subnet_is_passed_to_subnetwascreated_when_the_api_creates_a_new_subnet()
    {
        Event::fake();

        $sn = factory(DhcpSharedNetwork::class)->create();
        $subnet = factory(Subnet::class)->make(['dhcp_shared_network_id' => null]);

        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/dhcp/dhcp_shared_networks/' . $sn->id . '/subnets', $subnet->toArray());

        Event::assertDispatched(SubnetWasCreated::class, function($event) use ($subnet) {
            return $event->subnet->network_address === $subnet->network_address;
        });
    }

    public function test_api_can_create_subnet()
    {
        $sn = factory(DhcpSharedNetwork::class)->create();
        $subnet = factory(Subnet::class)->make(['dhcp_shared_network_id' => null]);

        $response = $this->actingAs($this->user, 'api')
            ->json('POST', '/api/dhcp/dhcp_shared_networks/' . $sn->id . '/subnets', $subnet->toArray());

        $response->assertJson([
            'start_ip' => $subnet->start_ip
        ]);

        $this->assertDatabaseHas('subnets', ['start_ip' => $subnet->start_ip]);

        $subnet = Subnet::whereStartIp($subnet->start_ip)->first();
        $this->assertCount(253, $subnet->ip_addresses);
    }

    public function test_subnet_knows_if_it_has_provisioning_records()
    {
        $provrec = factory(ProvisioningRecord::class)->create();

        $subnet = $provrec->ip_address->subnet;

        $this->assertTrue($subnet->has_provisioning_records);
    }

    public function test_subnet_knows_if_it_does_not_have_provisioning_records()
    {
        $subnet = factory(Subnet::class)->create();

        $this->assertFalse($subnet->has_provisioning_records);
    }
}
