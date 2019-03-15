<?php

namespace Tests\Feature;

use App\Subnet;
use Tests\TestCase;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IpAddressExclusionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @group dhcp-exclusions
     * @test
     */
    public function test_subnet_can_be_created_by_the_factory()
    {
        Event::fake();
        $subnet = factory(Subnet::class)->create();

        $start_ip = \Leth\IPAddress\IP\Address::factory($subnet->start_ip);
        $end_ip = \Leth\IPAddress\IP\Address::factory($subnet->end_ip);
        $network = \Leth\IPAddress\IPv4\NetworkAddress::factory($subnet->network_address, $subnet->cidr);

        foreach ($network as $ip)
        {
            $address = $ip->__toString();
            if ((($ip->compare_to($start_ip) == 0) || ($ip->compare_to($start_ip) == 1)) && (($ip->compare_to($end_ip) == 0) || ($ip->compare_to($end_ip) == -1)))
            {
                $subnet->ip_addresses()->create(['address' => $address]);
            }
        }

        $this->assertCount(253, $subnet->ip_addresses()->whereExcludeFromDhcp(false)->get());

        $subnet->ip_addresses[2]->exclude_from_dhcp = true;
        $subnet->ip_addresses[2]->save();

        $this->assertCount(252, $subnet->ip_addresses()->whereExcludeFromDhcp(false)->get());

    }

}
