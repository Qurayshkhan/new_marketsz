<?php

use App\Http\Controllers\Customer\SuiteController;

Route::prefix('customer')->middleware(['auth', 'customer'])->group(function () {

    Route::get('/dashboard', [SuiteController::class, 'index'])->name('customer.dashboard');

    Route::prefix('suite')->group(function () {
        Route::get('/action-required', [SuiteController::class, 'actionRequired'])->name('customer.suiteActionRequired');
    });

});
