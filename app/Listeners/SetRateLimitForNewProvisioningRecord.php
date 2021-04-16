<?php

namespace App\Listeners;

use App\Events\ServiceWasProvisioned;
use App\Jobs\SetRateLimit;
use App\Package;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SetRateLimitForNewProvisioningRecord implements ShouldQueue
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
        \Log::info('Handling a SetRateLimitForNewProvisioningRecord listener.');

        if ($event->package_id) {
            if ($event->package_id != $event->provisioning_record->package->id) {
                $package = Package::find($event->package_id);
                SetRateLimit::dispatch($package->id, $event->provisioning_record);
            }
        }
    }
}
