<?php

namespace App\Listeners;

use App\Events\ServiceWasProvisioned;
use App\Jobs\SetSubscriberId as SetSubscriberIdJob;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SetSubscriberId implements ShouldQueue
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

        SetSubscriberIdJob::dispatch($provisioning_record);
    }
}
