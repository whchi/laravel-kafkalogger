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
        $this->publishes([__DIR__ . '/../config/kafkalogger.php'], 'config');
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
