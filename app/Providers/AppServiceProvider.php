<?php

namespace App\Providers;

use App\Practice\Validation\Validator;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {


    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        $this->app->singleton(Validator::class, fn($app) =>
            new Validator($app['request']->all())
        );

        $this->app->singleton(Filesystem::class, function () {
            return Storage::disk('digitalocean');
        });
    }
}
