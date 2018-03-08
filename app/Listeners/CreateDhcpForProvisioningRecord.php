<?php

namespace App\Listeners;

use App\Events\ServiceWasProvisioned;
use App\GoldAccess\Dhcp\ManagementIp;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateDhcpForProvisioningRecord
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
     * @param  ServiceWasProvisioned  $event
     * @return void
     */
    public function handle(ServiceWasProvisioned $event)
    {
        $management_ip = new ManagementIp($event->provisioning_record);
        \Log::info('Creating dnsmasq config snippet for '.$event->provisioning_record);
        $management_ip->make();
    }
}
