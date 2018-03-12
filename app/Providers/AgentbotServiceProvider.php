<?php

namespace App\Providers;

use Jenssegers\Agent\Agent;
use Illuminate\Support\ServiceProvider;

class AgentbotServiceProvider extends ServiceProvider
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
        $this->app->bind('agentbot', function() {
            return new Agent();
        });
    }
}
