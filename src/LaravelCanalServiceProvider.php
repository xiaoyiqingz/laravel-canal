<?php

namespace LaravelCanal;

use Illuminate\Support\ServiceProvider;

class LaravelCanalServiceProvider extends ServiceProvider
{
    /**
     * Regiser canal service
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('canal', function ($app) {
            return new CanalManager($app);
        });
    }
}
