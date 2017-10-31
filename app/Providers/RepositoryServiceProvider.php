<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(\App\Repositories\Contracts\PlatformRepository::class, \App\Repositories\Eloquent\PlatformRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Contracts\ProductRepository::class, \App\Repositories\Eloquent\ProductRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Contracts\SalesdataRepository::class, \App\Repositories\Eloquent\SalesdataRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\Contracts\ChartsAccessRepository::class, \App\Repositories\Eloquent\ChartsAccessRepositoryEloquent::class);
        //:end-bindings:
    }
}
