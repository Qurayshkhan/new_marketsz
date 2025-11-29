<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PackageItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Web\WebsiteController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/welcome', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::middleware(['auth', 'verified', 'admin'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Imports Files

    Route::get('/imports', [ImportController::class, 'index'])->name('admin.import');
    Route::post('/imports', [ImportController::class, 'importUsers'])->name('admin.importUser');

    Route::prefix('package')->group(function () {
        Route::get('/', [PackageController::class, 'index'])->name('admin.packages');
        Route::get('/status-management', [PackageController::class, 'kanban'])->name('admin.packages.kanban');
        Route::get('/create', [PackageController::class, 'create'])->name('admin.packages.create');
        Route::post('/store', [PackageController::class, 'store'])->name('admin.packages.store');
        Route::get('/edit/{package}', [PackageController::class, 'edit'])->name('admin.packages.edit');
        Route::post('/update/{package}', [PackageController::class, 'update'])->name('admin.packages.update');
        Route::delete('/delete/{package}', [PackageController::class, 'destroy'])->name('admin.packages.delete');
        Route::put('/{package}/status', [PackageController::class, 'updateStatus'])->name('admin.packages.updateStatus');

        Route::put('/update-note/{package}', [PackageController::class, 'updateNote'])->name('admin.packages.updateNote');
    });

    // Package Item routes for individual item management
    Route::prefix('package-items')->group(function () {
        Route::post('/', [PackageItemController::class, 'store'])->name('admin.package-items.store');
        Route::get('/{packageItem}', [PackageItemController::class, 'show'])->name('admin.package-items.show');
        Route::put('/{packageItem}', [PackageItemController::class, 'update'])->name('admin.package-items.update');
        Route::delete('/{packageItem}', [PackageItemController::class, 'destroy'])->name('admin.package-items.destroy');
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('admin.users');
        Route::get('/create', [UserController::class, 'create'])->name('admin.users.createUser');
        Route::post('/store', [UserController::class, 'store'])->name('admin.users.storeUser');
        Route::get('/edit/{user}', [UserController::class, 'edit'])->name('admin.users.userEdit');
        Route::put('/update/{user}', [UserController::class, 'update'])->name('admin.users.userUpdate');

        // Route::get('/customers', [UserController::class, 'customers'])->name('admin.customers');
        Route::prefix('transactions')->group(function () {
            Route::get('/{user}', [TransactionController::class, 'userTransaction'])->name('admin.users.transactions');
            Route::put('/refund/{transaction}', [TransactionController::class, 'refundTransaction'])->name('admin.users.refundTransaction');
        });

        Route::prefix('packages')->group(function () {
            Route::get('/{user}', [PackageController::class, 'getUserPackages'])->name('admin.users.getUserPackages');
        });
    });
    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('admin.transactions.allTransactions');
    });
    Route::prefix('shipments')->group(function () {
        Route::get('/', [ShipmentController::class, 'index'])->name('admin.shipments');
        Route::get('edit/{ship}', [ShipmentController::class, 'edit'])->name('admin.shipments.edit');
        Route::post('/update/{ship}', [ShipmentController::class, 'update'])->name('admin.shipments.update');
        Route::get('/packages/{ship}', [ShipmentController::class, 'shipPackages'])->name('admin.shipments.packages');
        Route::get('/outbond', [ShipmentController::class, 'outbondRequests'])->name('admin.shipments.outbond');
    });

    // Coupon Management Routes
    Route::prefix('coupons')->group(function () {
        Route::get('/', [\App\Http\Controllers\CouponController::class, 'index'])->name('admin.coupons.index');
        Route::get('/create', [\App\Http\Controllers\CouponController::class, 'create'])->name('admin.coupons.create');
        Route::post('/store', [\App\Http\Controllers\CouponController::class, 'store'])->name('admin.coupons.store');
        Route::get('/edit/{coupon}', [\App\Http\Controllers\CouponController::class, 'edit'])->name('admin.coupons.edit');
        Route::post('/update/{coupon}', [\App\Http\Controllers\CouponController::class, 'update'])->name('admin.coupons.update');
        Route::delete('/delete/{coupon}', [\App\Http\Controllers\CouponController::class, 'destroy'])->name('admin.coupons.destroy');
        Route::put('/{coupon}/toggle-status', [\App\Http\Controllers\CouponController::class, 'toggleStatus'])->name('admin.coupons.toggle-status');
        Route::get('/stats', [\App\Http\Controllers\CouponController::class, 'usageStats'])->name('admin.coupons.stats');
        Route::post('/generate-code', [\App\Http\Controllers\CouponController::class, 'generateCode'])->name('admin.coupons.generate-code');
    });

    // Loyalty Program Management Routes
    Route::prefix('loyalty')->group(function () {
        Route::get('/', [\App\Http\Controllers\LoyaltyController::class, 'index'])->name('admin.loyalty.index');
        Route::get('/rules', [\App\Http\Controllers\LoyaltyController::class, 'rules'])->name('admin.loyalty.rules');
        Route::post('/rules/store', [\App\Http\Controllers\LoyaltyController::class, 'storeRule'])->name('admin.loyalty.rules.store');
        Route::post('/rules/{rule}/update', [\App\Http\Controllers\LoyaltyController::class, 'updateRule'])->name('admin.loyalty.rules.update');
        Route::delete('/rules/{rule}/delete', [\App\Http\Controllers\LoyaltyController::class, 'destroyRule'])->name('admin.loyalty.rules.destroy');
        Route::put('/rules/{rule}/toggle-status', [\App\Http\Controllers\LoyaltyController::class, 'toggleRuleStatus'])->name('admin.loyalty.rules.toggle-status');
        Route::get('/transactions', [\App\Http\Controllers\LoyaltyController::class, 'transactions'])->name('admin.loyalty.transactions');
        Route::get('/users', [\App\Http\Controllers\LoyaltyController::class, 'users'])->name('admin.loyalty.users');
    });
});
// website routes
Route::get("/", [WebsiteController::class, "index"])->name('web.home');
Route::get("/calculator", [WebsiteController::class, "calculator"])->name('web.calculator');
Route::get("/contact", [WebsiteController::class, "contact"])->name('web.contact');
Route::get('/about', [WebsiteController::class, 'about'])->name('web.about');
Route::get('/faqs', [WebsiteController::class, 'faqs'])->name('web.faqs');
Route::get('/term', [WebsiteController::class, 'terms'])->name('web.terms');
Route::get('/privacy', [WebsiteController::class, 'privacy'])->name('web.privacy');

// routes/web.php
Route::post('/calculate-shipping', [WebsiteController::class, 'calculate']);


require __DIR__ . '/auth.php';
require __DIR__ . '/customer.php';
