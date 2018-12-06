<?php

namespace App\Providers;

use App\GoldAccess\Dhcp\Dhcpbot;
use App\GoldAccess\Dhcp\DnsmasqServer;
use Illuminate\Support\ServiceProvider;

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
        $this->app->bind('dhcp', function() {
            return new DnsmasqServer();
        });

        $this->app->bind('dhcpbot', function() {
            return new Dhcpbot();
        });
    }
}
