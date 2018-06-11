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
        // Goat
        'App\Events\SubnetWasCreated' => [
            'App\Listeners\CreateDhcpIpAddresses',
        ],
        'App\Events\ServiceWasProvisioned' => [
            'App\Listeners\CreateDhcpForProvisioningRecord',
        ],
        'App\Events\DeletingProvisioningRecord' => [
            'App\Listeners\RemoveManagementIp',
        ],
        'App\Events\ProvisioningRecordWasUpdated' => [
            'App\Listeners\UpdateDhcpServer',
        ],

        // Laravel Auth
        'Illuminate\Auth\Events\Registered' => [
            'App\Listeners\LogRegisteredUser',
        ],

        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\LogSuccessfulLogin',
        ],

        'Illuminate\Auth\Events\Failed' => [
            'App\Listeners\LogFailedLogin',
        ],

        'Illuminate\Auth\Events\Logout' => [
            'App\Listeners\LogSuccessfulLogout',
        ],

        'Illuminate\Auth\Events\Lockout' => [
            'App\Listeners\LogLockout',
        ],

        'Illuminate\Auth\Events\PasswordReset' => [
            'App\Listeners\LogPasswordReset',
        ],

        // Spatie MediaLibrary
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
