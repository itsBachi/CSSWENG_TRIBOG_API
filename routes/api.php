<?php

use App\Http\Controllers\ProductsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::get('/', function () {
        return 'Welcome to Tribog API';
    });

    Route::get('/products-collection', [ProductsController::class, 'getAllPaginated']);
});
