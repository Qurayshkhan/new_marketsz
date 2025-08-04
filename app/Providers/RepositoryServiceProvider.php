<?php

namespace App\Providers;
use App\Interfaces\PackageFileInterface;
use App\Interfaces\PackageInterface;
use App\Interfaces\PackageItemInterface;
use App\Interfaces\PaymentMethodInterface;
use App\Interfaces\ShippingPreferencesInterface;
use App\Interfaces\UserAddressInterface;
use App\Repositories\PackageItemRepository;
use App\Repositories\PackageRepository;
use App\Repositories\PaymentMethodRepository;
use App\Repositories\ShippingPreferencesRepository;
use App\Repositories\UserAddressRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;
use League\Uri\Contracts\UserInfoInterface;

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
        $this->app->bind(UserInfoInterface::class, UserRepository::class);
        $this->app->bind(UserAddressInterface::class, UserAddressRepository::class);
        $this->app->bind(PaymentMethodInterface::class, PaymentMethodRepository::class);

        $this->app->bind(ShippingPreferencesInterface::class, ShippingPreferencesRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
