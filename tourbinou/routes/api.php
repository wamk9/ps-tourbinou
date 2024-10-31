<?php

use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\DestinationController;
use App\Http\Controllers\Api\Admin\TourController;
use App\Http\Controllers\Api\StoreController;
use Illuminate\Support\Facades\Route;


Route::group([
        'middleware' => 'api',
        'prefix' => 'store'
    ], function () {
        Route::get('/', [StoreController::class, 'index'])->name('api.store.index');
});


Route::group([
        'middleware' => 'api'
    ], function () {
        Route::post('/login', [AuthController::class, 'login'])->name('api.admin.login');
});

Route::group([
        'middleware' => 'api',
        'prefix' => 'admin'
    ], function () {
        Route::get('/tour', [TourController::class, 'index'])->name('api.admin.tour.index');
        Route::get('/tour/{id}', [TourController::class, 'show'])->name('api.admin.tour.show');
        Route::post('/tour', [TourController::class, 'store'])->name('api.admin.tour.store');
        Route::put('/tour/{id}', [TourController::class, 'update'])->name('api.admin.tour.update');
        Route::delete('/tour/{id}', [TourController::class, 'delete'])->name('api.admin.tour.delete');

        Route::get('/destination', [DestinationController::class, 'index'])->name('api.admin.destination.index');
        Route::get('/destination/{id}', [DestinationController::class, 'show'])->name('api.admin.destination.show');
        Route::post('/destination', [DestinationController::class, 'store'])->name('api.admin.destination.store');
        Route::put('/destination/{id}', [DestinationController::class, 'update'])->name('api.admin.destination.update');
        Route::delete('/destination/{id}', [DestinationController::class, 'delete'])->name('api.admin.destination.delete');

        Route::get('/hour', [TourController::class, 'hourIndex'])->name('api.admin.hour.index');
        Route::get('/state', [DestinationController::class, 'stateIndex'])->name('api.admin.state.index');
        Route::get('/destination-list', [DestinationController::class, 'list'])->name('api.admin.destination.list');


        Route::post('/refresh', [AuthController::class, 'refresh'])->name('api.admin.refresh');
        Route::post('/logout', [AuthController::class, 'logout'])->name('api.admin.logout');
});

Route::fallback(function(){
    return response()->json([
        'message' => 'Resource not found'
    ], 404);
});
