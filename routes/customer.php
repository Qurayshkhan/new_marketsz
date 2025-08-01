<?php

use App\Http\Controllers\Customer\SuiteController;
use App\Http\Controllers\PackageController;

Route::prefix('customer')->middleware(['auth', 'customer'])->group(function () {

    Route::get('/dashboard', [SuiteController::class, 'index'])->name('customer.dashboard');

    Route::prefix('suite')->group(function () {
        Route::get('/action-required', [SuiteController::class, 'actionRequired'])->name('customer.suiteActionRequired');
        Route::post('/package/add-note', [PackageController::class, 'addNote'])->name('customer.packageAddNote');
        Route::post('/package/upload-invoices', [PackageController::class, 'uploadInvoices'])->name('customers.packageUploadInvoices');
    });

});
