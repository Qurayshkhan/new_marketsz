<?php

use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Customer\PaymentMethodController;
use App\Http\Controllers\Customer\ShippingPreferenceController;
use App\Http\Controllers\Customer\SuiteController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserAddressController;

Route::prefix('customer')->middleware(['auth', 'customer'])->group(function () {

    Route::get('/dashboard', [SuiteController::class, 'index'])->name('customer.dashboard');

    Route::prefix('suite')->group(function () {
        Route::get('/action-required', [SuiteController::class, 'actionRequired'])->name('customer.suiteActionRequired');

        Route::get('/in-review', [SuiteController::class, 'inReview'])->name('customer.suite.inReview');

        Route::get('/ready-to-send', [SuiteController::class, 'readyToSend'])->name('customer.suite.readyToSend');

        Route::get('/view-all', [SuiteController::class, 'viewAll'])->name('customer.suite.viewAll');

        Route::post('/package/add-note', [SuiteController::class, 'addNote'])->name('customer.packageAddNote');
        Route::post('/package/upload-invoices', [SuiteController::class, 'uploadInvoices'])->name('customers.packageUploadInvoices');
        Route::get('/package/photos', [SuiteController::class, 'getPackagePhotos'])->name('customers.packageGetPhotos');
        Route::post('/package/special-request', [SuiteController::class, 'setSpecialRequest'])->name('customer.packageSetSpecialRequest');
    });

    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::prefix('account-setting')->group(function () {

        Route::get('/profile', [ProfileController::class, 'customerProfile'])->name('customer.account.profile');

        Route::patch('/profile', [ProfileController::class, 'update'])->name('customer.account.update');

        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('customer.account.destroy');

        Route::put('password', [PasswordController::class, 'update'])->name('customer.account.password.update');


        Route::get('/address-book', [UserAddressController::class, 'index'])->name('customer.account.addressBook');

        Route::prefix('addresses')->group(function () {
            Route::post('/', [UserAddressController::class, 'store'])->name('customer.addresses.store');
            Route::put('/{address}', [UserAddressController::class, 'update'])->name('customer.addresses.update');
            Route::delete('/{address}', [UserAddressController::class, 'destroy'])->name('customer.addresses.destroy');
            Route::put('/{address}/set-default', [UserAddressController::class, 'setDefault'])->name('customer.addresses.setDefault');
        });

        Route::prefix('payments')->group(function () {
            Route::get('/', [PaymentMethodController::class, 'paymentMethods'])->name('customer.payment.paymentMethods');

            Route::post('/add-card', [PaymentMethodController::class, 'storeCard'])->name('customer.card.add');

            Route::put('/customer/card/set-default/{id}', [PaymentMethodController::class, 'setDefault'])->name('customer.card.setDefault');
            Route::delete('/customer/card/{id}', [PaymentMethodController::class, 'destroy'])->name('customer.card.delete');

            Route::put('/card/update/{id}', [PaymentMethodController::class, 'updateCard'])->name('customer.card.update');
        });

        Route::prefix('shipping-preference')->group(function () {

            Route::get('/', [ShippingPreferenceController::class, 'index'])->name('customer.shippingPreferences.preference');

            Route::post('/preference-address', [ShippingPreferenceController::class, 'setPreferAddress'])->name('customer.preference.changeAddress');

            Route::post('/preferences-save-changes', [ShippingPreferenceController::class, 'saveChangePreferences'])->name('customer.preferences.saveChange');
        });
    });

});
