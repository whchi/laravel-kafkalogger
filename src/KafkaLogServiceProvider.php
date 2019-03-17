<?php

namespace Cw\KafkaLogger;

use Illuminate\Foundation\Application as LaravelApplication;
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
        $source = realpath($raw = __DIR__ . '/../config/kafkalogger.php') ?: $raw;
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('kafkalogger.php')]);
        }
        $this->mergeConfigFrom($source, 'kafkalogger');

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
