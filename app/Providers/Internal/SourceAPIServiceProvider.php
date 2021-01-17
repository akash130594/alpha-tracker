<?php

namespace App\Providers\Internal;

use App\Library\Services\SourceAPI\FulcrumAPIService;
use App\Library\Services\SourceAPI\SourceAPIService;
use App\Library\Services\SourceAPI\SourceAPIServiceInterface;
use Illuminate\Support\ServiceProvider;

class SourceAPIServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(SourceAPIServiceInterface::class, function ($app) {
            return new SourceAPIService();
        });

    }
}
