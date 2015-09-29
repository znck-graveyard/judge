<?php

namespace Judge\Providers;

use Illuminate\Support\ServiceProvider;
use Judge\Contracts\Sandbox;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(Sandbox::class, \Judge\Sandbox::class);
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
