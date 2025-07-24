<?php

namespace App\Providers;
use App\Interfaces\PackageFileInterface;
use App\Interfaces\PackageInterface;
use App\Interfaces\PackageItemInterface;
use App\Repositories\PackageItemRepository;
use App\Repositories\PackageRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(PackageInterface::class, PackageRepository::class);
        $this->app->bind(PackageItemInterface::class, PackageItemRepository::class);
        $this->app->bind(PackageFileInterface::class, PackageRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
