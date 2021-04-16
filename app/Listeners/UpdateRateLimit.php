<?php

namespace App\Listeners;

use App\Events\ProvisioningRecordWasUpdated;
use App\Jobs\SetRateLimit;
use App\Package;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateRateLimit implements ShouldQueue
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
        \Log::info('Handling an UpdateRateLimit listener.');
        if ($event->package_id) {
            if ($event->package_id != $event->provisioning_record->package->id) {
                $package = Package::find($event->package_id);
                SetRateLimit::dispatch($package->id, $event->provisioning_record);
            }
        }
    }
}
