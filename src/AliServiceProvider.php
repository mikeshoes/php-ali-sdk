<?php

namespace Wdy\AliService;


use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;
use Wdy\AliService\ServiceManager;

class AliServiceProvider extends ServiceProvider implements DeferrableProvider
{

    /**
     * Bootstrap the configuration
     *
     * @return void
     */
    public function boot()
    {
        $source = realpath(__DIR__ . '/../config/aliyun.php');

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('aliyun.php')]);
        }

        $this->mergeConfigFrom($source, 'aliyun');
    }

    public function register()
    {
        $this->app->singleton('ali_service', function ($app) {
            $config = $app->make('config')->get('aliyun');
            return new ServiceManager($config);
        });
    }

    public function provides()
    {
        return [
            'ali_service',
        ];
    }
}