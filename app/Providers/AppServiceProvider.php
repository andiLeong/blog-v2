<?php

namespace App\Providers;

use App\Practice\Validation\Validator;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Testing\Assert as PHPUnit;
use Illuminate\Testing\TestResponse;

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

        Str::macro('pluralWords', function ($word, $number, $separator = ' ') {
            return $number . $separator . Str::plural($word, $number);
        });

        TestResponse::macro('assertJsonValidationMessageFor', function ($key, $rule = null, $message = null, $responseKey = 'errors') {

            $this->assertJsonValidationErrorFor($key);

            $jsonErrors = Arr::get($this->json(), $responseKey) ?? [];

            if ($message === null && $rule !== null) {
                $message = str_replace(':attribute', $key, trans('validation.' . $rule));
            }

            if (is_null($message) && is_null($rule)) {
                return PHPUnit::fail('No message or rule specified.');
            }

            PHPUnit::assertTrue(
                in_array($message, $jsonErrors[$key]),
                $key . ' is not within the json response.'
            );

        });
    }
}
