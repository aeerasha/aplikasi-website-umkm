<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DashboardController;


/*
|--------------------------------------------------------------------------
| Public Route
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| OWNER ONLY
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:owner'])->group(function () {

    // Dashboard Owner
    Route::get('/dashboard-owner', [DashboardController::class, 'index'])
        ->name('owner.dashboard');

    // CRUD Product
    Route::resource('products', ProductController::class);

    // Ingredient
     Route::resource('ingredients', IngredientController::class)
        ->except(['index']);
    Route::patch('/ingredients/{ingredient}/increase-stock', [IngredientController::class, 'increaseStock'])
    ->name('ingredients.increaseStock');

    Route::patch('/ingredients/{ingredient}/decrease-stock', [IngredientController::class, 'decreaseStock'])
    ->name('ingredients.decreaseStock');

    // Pegawai
    Route::resource('employees', EmployeeController::class);

    // View Customer Review
    Route::get('/owner/reviews', [ReviewController::class, 'ownerIndex'])
        ->name('owner.reviews.index');

    Route::get('/sales-report', [DashboardController::class, 'salesReport'])
    ->name('sales.report');

    Route::get('/best-products', [DashboardController::class, 'bestProducts'])
    ->name('best.products');

});


/*
|--------------------------------------------------------------------------
| PEGAWAI ONLY
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:pegawai'])->group(function () {

    // Dashboard Pegawai
    Route::get('/dashboard-pegawai', function () {
        return view('pegawai.dashboard');
    })->name('pegawai.dashboard');

    Route::get('/employee/orders', [OrderController::class, 'index'])
        ->name('employee.orders.index');

    Route::patch('/employee/orders/{order}/status', [OrderController::class, 'updateStatus'])
        ->name('employee.orders.updateStatus');

});


/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::get('/ingredients', [IngredientController::class, 'index'])
        ->name('ingredients.index');

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

});


/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';