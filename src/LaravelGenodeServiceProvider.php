<?php

namespace KevinOngko\LaravelGenode;

use Illuminate\Support\ServiceProvider;
use KevinOngko\LaravelGenode\Commands\MakeModule;

class LaravelGenodeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $modules = collect(config('module.active'));

        $modules->each(function ($module) {
            $this->app->register('Modules\\'.studly_case($module).'\Providers\\'.studly_case($module).'ServiceProvider');
        });

        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeModule::class
            ]);
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
