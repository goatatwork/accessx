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

        $ip = $event->provisioning_record->ip_address->address;
        $username = $event->provisioning_record->service_location->customer->customer_name;
        $userid = $event->provisioning_record->service_location->customer->id;
        $servicelocationname = $event->provisioning_record->service_location->name;
        $servicelocationid = $event->provisioning_record->service_location->id;

        app('logbot')->log('Creating management IP (' .
            $ip .
            ') for user ' .
            $username . '(' . $userid . ') at service location ' .
            $servicelocationname . '(' . $servicelocationid . ').'
        );

        $management_ip->make();
    }
}
