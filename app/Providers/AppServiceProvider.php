<?php

namespace App\Providers;

use Cloudinary;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
      Cloudinary::config(array(
        "cloud_name" => 'dsteinberg',
        "api_key" => '144924284592464',
        "api_secret" => 'eSyrFKn3rTwGpe9eHHOXnpj3h54'
      ));
    }
}
