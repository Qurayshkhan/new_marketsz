<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Route;

class RouteServiceProvider extends ServiceProvider
{
    public const ADMIN_HOME = '/dashboard';

    public const CUSTOMER_HOME = '/customer/dashboard';

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Route::middleware('web')
            ->domain(env('APP_URL'))
            ->group(base_path('routes/auth.php'))
            ->group(base_path('routes/customer.php'))
            ->group(base_path('routes/web.php'));

    }
}
