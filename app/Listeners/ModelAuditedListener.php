<?php

namespace App\Listeners;

use OwenIt\Auditing\Events\Audited;
use App\GoldAccess\Dhcp\ManagementIp;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ModelAuditedListener
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
     * @param  Audited  $event
     * @return void
     */
    public function handle(Audited $event)
    {
        // This works. Commenting it out as we're firing all events from the controllers
        //
        // if ( str_is('App\\ProvisioningRecord', $event->audit->auditable_type) && $event->audit->event == 'updated' ) {

        //     $provisioning_record = $event->model;

        //     $dhcp_for_this_record = new ManagementIp($provisioning_record);

        //     $dhcp_for_this_record->make();

        //     $this->logIt($provisioning_record);

        //     app('dockerbot')->containerRestart(config('goldaccess.dockerbot.services.dhcp.container_name'));
        // }
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
