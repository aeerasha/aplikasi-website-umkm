<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;



Route::get('/', function () {
    return view('welcome');
});

Route::resource('products', ProductController::class);
Route::get('/dashboard', [DashboardController::class, 'index']);