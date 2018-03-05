<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\GoldAccess\Dhcp\DnsmasqServer;

class DhcpServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // \Log::info('boot() method on DhcpServiceProvider was called');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // \Log::info('register() method on DhcpServiceProvider was called');

        $this->app->bind('dhcp', function() {
            return new DnsmasqServer();
        });
    }
}
