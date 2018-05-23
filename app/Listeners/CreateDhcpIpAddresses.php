<?php

namespace App\Listeners;

use App\Events\SubnetWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Leth\IPAddress\IP, Leth\IPAddress\IPv4, Leth\IPAddress\IPv6;

class CreateDhcpIpAddresses implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  SubnetWasCreated  $event
     * @return void
     */
    public function handle(SubnetWasCreated $event)
    {
        $start_ip = IP\Address::factory($event->subnet->start_ip);
        $end_ip = IP\Address::factory($event->subnet->end_ip);
        $network = IPv4\NetworkAddress::factory($event->subnet->network_address, $event->subnet->cidr);

        foreach ($network as $ip)
        {
            $address = $ip->__toString();
            if ((($ip->compare_to($start_ip) == 0) || ($ip->compare_to($start_ip) == 1)) && (($ip->compare_to($end_ip) == 0) || ($ip->compare_to($end_ip) == -1)))
            {
                $event->subnet->ip_addresses()->create(['address' => $address]);
            }
        }
        return $event->subnet->ip_addresses()->get();
    }
}
