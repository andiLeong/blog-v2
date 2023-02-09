<?php

namespace App\Providers;

use App\Practice\Validation\Validator;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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

        Str::macro('pluralWords', function($word, $number, $separator = ' '){
            return $number . $separator . Str::plural($word, $number);
        });

        UploadedFile::macro('setUploadedPath', function($path){
           $this->uploadedPath = $path;
        });
    }
}
