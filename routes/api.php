<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('products', \App\Http\Controllers\ProductController::class);
Route::apiResource('customers', \App\Http\Controllers\CustomerController::class);
Route::apiResource('transactions', \App\Http\Controllers\TransactionController::class);
