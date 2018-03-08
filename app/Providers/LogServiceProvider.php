<?php

namespace App\Providers;

use App\GoldAccess\Utilities\Logger;
use Illuminate\Support\ServiceProvider;

class LogServiceProvider extends ServiceProvider
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
        $this->app->bind('thelogger', function() {
            return new Logger();
        });
    }
}
