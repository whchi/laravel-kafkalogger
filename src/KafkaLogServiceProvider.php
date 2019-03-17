<?php

namespace Cw\KafkaLogger;

use Illuminate\Support\ServiceProvider;

class KafkaLogServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->configureMonologUsing(function ($monolog) {
            $monolog->pushHandler(new Logger);
        });
    }
}
