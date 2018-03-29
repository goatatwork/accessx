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
        \Log::info($event->audit->auditable_type . ' was ' . $event->audit->event);
        if ( str_is('App\\ProvisioningRecord', $event->audit->auditable_type) && $event->audit->event == 'updated' ) {

            $provisioning_record = $event->model;

            $dhcp_for_this_record = new ManagementIp($provisioning_record);

            app('logbot')->log('PROVISIONING RECORD IS ABOUT TO BE EDITED');

            $dhcp_for_this_record->make();

            app('logbot')->log('PROVISIONING RECORD HAS BEEN EDITED');

            app('dockerbot')->containerRestart(config('goldaccess.dockerbot.services.dhcp.container_name'));
        }
    }
}
