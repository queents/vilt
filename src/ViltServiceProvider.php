<?php

namespace Queents\Vilt;

use Illuminate\Support\ServiceProvider;
use Queents\Vilt\Console\InstallVilt;

class ViltServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            InstallVilt::class
        ]);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
