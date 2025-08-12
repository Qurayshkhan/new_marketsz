<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ProfileController;
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
        Route::get('/create', [PackageController::class, 'create'])->name('admin.packages.create');
        Route::post('/store', [PackageController::class, 'store'])->name('admin.packages.store');
        Route::get('/edit/{package}', [PackageController::class, 'edit'])->name('admin.packages.edit');
        Route::delete('/delete/{package}', [PackageController::class, 'destroy'])->name('admin.packages.delete');
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('admin.users');
        Route::get('/create', [UserController::class, 'create'])->name('admin.users.createUser');
        Route::post('/store', [UserController::class, 'store'])->name('admin.users.storeUser');
        Route::get('/edit/{user}', [UserController::class, 'edit'])->name('admin.users.userEdit');
        Route::put('/update/{user}', [UserController::class, 'update'])->name('admin.users.userUpdate');

        Route::prefix('transactions')->group(function () {
            Route::get('/{user}', [TransactionController::class, 'index'])->name('admin.users.transactions');
            Route::put('/refund/{transaction}', [TransactionController::class, 'refundTransaction'])->name('admin.users.refundTransaction');
        });
    });
});
// Route::middleware(['auth', 'admin'])->group(function () {
// });


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
