<?php

use App\Http\Controllers\ProductsController;
use App\Http\Controllers\DeliveriesController;
use App\Http\Controllers\TransactionsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::get('/', function () {
        return 'Welcome to Tribog API';
    });

    Route::get('/products-collection', [ProductsController::class, 'getAllPaginated']);
    Route::post('/products-create', [ProductsController::class, 'create']);
    Route::put('/products-update/{id}', [ProductsController::class, 'update']);
    Route::delete('/products-delete/{id}', [ProductsController::class, 'delete']);

    Route::get('/deliveries-collection', [DeliveriesController::class, 'getAllPaginated']);
    Route::get('/deliveries-update/{id}', [DeliveriesController::class, 'update']);
    Route::get('/deliveries-delete/{id}', [DeliveriesController::class, 'delete']);

    Route::get('/transactions-collection', [TransactionsController::class, 'getAllPaginated']);
    Route::get('/transactions-update/{id}', [TransactionsController::class, 'update']);
    Route::get('/transactions-delete/{id}', [TransactionsController::class, 'delete']);
});
