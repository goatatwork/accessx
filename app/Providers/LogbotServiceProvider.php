<?php

namespace App\Providers;

use App\GoldAccess\Utilities\Logger;
use Illuminate\Support\ServiceProvider;

class LogbotServiceProvider extends ServiceProvider
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
        $this->app->bind('logbot', function() {
            return new Logger();
        });
    }
}
