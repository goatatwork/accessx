<?php

namespace App\Providers;

use App\GoldAccess\Docker\Dockerbot;
use Illuminate\Support\ServiceProvider;

class DockerbotServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('dockerbot', function() {
            return new Dockerbot();
        });
    }
}
