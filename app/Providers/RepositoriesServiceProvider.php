<?php

namespace App\Providers;

use App\Repositories\CountryRepository;
use App\Repositories\CountryRepositoryInterface;
use App\Repositories\StateRepository;
use App\Repositories\StateRepositoryInterface;
use App\Repositories\VirusDataRepository;
use App\Repositories\VirusDataRepositoryInterface;
use App\Repositories\VirusDataTypeRepository;
use App\Repositories\VirusDataTypeRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
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
        $this->app->bind(CountryRepositoryInterface::class, CountryRepository::class);
        $this->app->bind(StateRepositoryInterface::class, StateRepository::class);
        $this->app->bind(VirusDataRepositoryInterface::class, VirusDataRepository::class);
        $this->app->bind(VirusDataTypeRepositoryInterface::class, VirusDataTypeRepository::class);
    }
}
