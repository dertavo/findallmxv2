<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;

class AppServiceProvider extends ServiceProvider
{
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
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //

        if (env('APP_ENV') === 'production') {
            \URL::forceScheme('https');
        }

        Validator::extend('no_spaces', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^\S+$/', $value);
        });
    }
}
