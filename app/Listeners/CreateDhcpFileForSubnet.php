<?php

namespace App\Listeners;

use App\Events\SubnetWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateDhcpFileForSubnet
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
     * @param  SubnetWasCreated  $event
     * @return void
     */
    public function handle(SubnetWasCreated $event)
    {
        if ( ! $event->subnet->is_management ) {

            app('dhcpbot')->build($event->subnet);
            app('dhcpbot')->deploy($event->subnet);

        }
    }
}
