<?php

namespace App\Providers;

use App\Classes\PacktAPI;
use Illuminate\Support\ServiceProvider;

class CustomFacadesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('packtapi',function(){
            return new PacktAPI();
        });
    }
}
