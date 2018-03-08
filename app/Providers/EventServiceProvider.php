<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SubnetWasCreated' => [
            'App\Listeners\CreateDhcpIpAddresses',
        ],
        'App\Events\ServiceWasProvisioned' => [
            'App\Listeners\CreateDhcpForProvisioningRecord',
        ],
        'Spatie\MediaLibrary\Events\MediaHasBeenAdded' => [
            'App\Listeners\MediaLogger'
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
