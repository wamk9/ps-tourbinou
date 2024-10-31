<?php

use App\Http\Controllers\Web\Admin\AuthController;
use App\Http\Controllers\Web\Admin\DestinationController;
use App\Http\Controllers\Web\Admin\TourController;
use App\Http\Controllers\Web\StoreController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {
    Route::get('/login', [AuthController::class, 'pageLogin'])->name('pages.admin.login');
});

Route::middleware(['web'])->group(function () {
    Route::get('/', [StoreController::class, 'pageIndex'])->name('pages.store.home');
});

Route::group([
        'middleware' => 'web',
        'prefix' => 'admin'
    ], function () {
        Route::get('/tour', [TourController::class, 'pageIndex'])->name('pages.admin.tour.index');
        Route::get('/tour/create', [TourController::class, 'pageStore'])->name('pages.admin.tour.store');
        Route::get('/tour/edit/{id}', [TourController::class, 'pageUpdate'])->name('pages.admin.tour.update');

        Route::get('/destination', [DestinationController::class, 'pageIndex'])->name('pages.admin.destination.index');
        Route::get('/destination/create', [DestinationController::class, 'pageStore'])->name('pages.admin.destination.store');
        Route::get('/destination/edit/{id}', [DestinationController::class, 'pageUpdate'])->name('pages.admin.destination.update');

        Route::get('/logout', [AuthController::class, 'pageLogout'])->name('pages.admin.logout');
});
