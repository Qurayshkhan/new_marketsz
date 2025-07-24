<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ProfileController;
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


Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
Route::middleware('auth')->group(function () {
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
    });
});


// website routes
Route::get("/", [WebsiteController::class, "index"])->name('web.home');
Route::get("/calculator", [WebsiteController::class, "calculator"])->name('web.calculator');
Route::get("/contact", [WebsiteController::class, "contact"])->name('web.contact');
Route::get('/about', [WebsiteController::class, 'about'])->name('web.about');
require __DIR__ . '/auth.php';
