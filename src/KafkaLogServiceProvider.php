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
        $app = realpath($raw = __DIR__ . '/app') ?: $raw;
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('kafkalogger.php')]);
            $this->publishes([$app => app_path()]);
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
        $this->app->bind(
            'KafkaLogger', function ($app) {
                return new KafkaLogger;
            }
        );
    }
}
