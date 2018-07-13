<?php

namespace App\Providers;

use Config;
use App\GaSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\Resource;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Resource::withoutWrapping();

        if (Schema::hasTable('ga_settings')) {
            foreach (GaSetting::all() as $setting) {
                \Log::info('AppServiceProvider registering config-->goldaccess.settings.' . $setting->name);
                Config::set('goldaccess.settings.' . $setting->name, $setting->value);
            }
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
