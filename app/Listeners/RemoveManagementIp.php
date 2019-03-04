<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use App\Events\DeletingProvisioningRecord;
use Illuminate\Contracts\Queue\ShouldQueue;

class RemoveManagementIp
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
     * @param  DeletingProvisioningRecord  $event
     * @return void
     */
    public function handle(DeletingProvisioningRecord $event)
    {
        app('dhcpbot')->destroy($event->provisioning_record, 'dhcp_management_ip');

        $ip = $event->provisioning_record->ip_address->address;
        $username = $event->provisioning_record->service_location->customer->customer_name;
        $userid = $event->provisioning_record->service_location->customer->id;
        $servicelocationname = $event->provisioning_record->service_location->name;
        $servicelocationid = $event->provisioning_record->service_location->id;

        app('logbot')->log('Deleting management IP (' .
            $ip .
            ') for user ' .
            $username . '(' . $userid . ') at service location ' .
            $servicelocationname . '(' . $servicelocationid . ').'
        );
    }
}
