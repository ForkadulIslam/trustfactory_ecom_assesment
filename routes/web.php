<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('shop', [DashboardController::class, 'shop'])
    ->middleware(['auth', 'verified'])
    ->name('shop');

Route::get('cart', [CartController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('cart.index');

Route::post('cart/{product}', [CartController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('cart.store');

Route::patch('cart/{cart_item}', [CartController::class, 'update'])
    ->middleware(['auth', 'verified'])
    ->name('cart.update');

Route::delete('cart/{cart_item}', [CartController::class, 'destroy'])
    ->middleware(['auth', 'verified'])
    ->name('cart.destroy');

Route::post('/orders', [OrderController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('orders.store');

Route::get('/orders', [OrderController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('orders.index');


require __DIR__.'/settings.php';
