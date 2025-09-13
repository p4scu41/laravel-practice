<?php

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\BookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::apiResource('books', BookController::class)->middleware('auth:sanctum');

    // Route::prefix('books')
    //     ->name('books.')
    //     ->controller(BookController::class)
    //     ->middleware('auth:sanctum')
    //     ->group(function () {
    //         Route::get('/', 'index')->name('index');
    //         Route::post('/', 'store')->name('store');
    //         Route::get('/{book}', 'show')->name('show');
    //         Route::match(['put', 'patch'], '/{book}', 'update')->name('update');
    //         Route::delete('/{book}', 'destroy')->name('destroy');
    //     });

    Route::prefix('auth')->controller(AuthController::class)->group(function () {
        Route::post('/login', 'store')->name('login');
        Route::delete('/logout', 'destroy')->middleware('auth:sanctum');
    });
});
