<?php

namespace KevinOngko\LaravelGenode;

use Illuminate\Support\ServiceProvider;
use KevinOngko\LaravelGenode\Commands\MakeModule;
use KevinOngko\LaravelGenode\Commands\MakeModel;

class LaravelGenodeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerConfig();
        $this->registerCommands();
        $this->registerModules();
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

    /**
     * Register LaravelGenode modules.
     */
    protected function registerModules()
    {
        $modules = collect(config('module.active'));

        $modules->each(function ($module) {
            $this->app->register('Modules\\'.studly_case($module).'\Providers\\'.studly_case($module).'ServiceProvider');
        });
    }

    /**
     * Register LaravelGenode commands.
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeModule::class,
                MakeModel::class
            ]);
        }
    }

    /**
     * Register LaravelGenode config.
     */
    protected function registerConfig()
    {
        $configPath = __DIR__.'/../config/module.php';
        $this->mergeConfigFrom($configPath, 'module');
        $this->publishes([
            $configPath => config_path('module.php'),
        ]);
    }
}
