<?php

namespace App\Listeners;

use App\Events\ServiceWasProvisioned;
use App\Jobs\SetRateLimit as SetRateLimitJob;
use App\Package;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;

class SetRateLimit implements ShouldQueue
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
        $provisioning_record = $event->provisioning_record;
        $form_data = $event->form_data;

        $text = (Arr::has($form_data,'package_id')) ? 'SetRateLimit - Yes package_id' : 'SetRateLimit - No package_id';

        if (Arr::has($form_data, 'package_id')) {

            $package = Package::find($form_data['package_id']);
            $provisioning_record->packages()->save($package);

            SetRateLimitJob::dispatch($package->id, $provisioning_record);

        }


    }
}
