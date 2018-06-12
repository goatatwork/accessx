<?php

namespace App\Listeners;

use App\GoldAccess\Dhcp\ManagementIp;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\ProvisioningRecordWasUpdated;

class UpdateDhcpServer implements ShouldQueue
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
     * @param  ProvisioningRecordWasUpdated  $event
     * @return void
     */
    public function handle(ProvisioningRecordWasUpdated $event)
    {
        $dhcp_for_this_record = new ManagementIp($event->provisioning_record);

        $dhcp_for_this_record->make();

        $this->logIt($event->provisioning_record);

        app('dockerbot')->containerRestart(config('goldaccess.dockerbot.services.dhcp.container_name'));
    }

    /**
     * @param  \App\ProvisioningRecord $provisioning_record
     * @return  void
     */
    protected function logIt($provisioning_record)
    {
        $customer = $provisioning_record->service_location->customer->customer_name;
        $package = $provisioning_record->ont_profile->name;

        app('logbot')->log($customer . ' is now on the ' . $package . ' package.');
    }
}
