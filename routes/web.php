<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FridgeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RestockController;
use App\Http\Controllers\ShareController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/fridges', [FridgeController::class, 'index'])->name('fridges.index');
    Route::post('/fridges', [FridgeController::class, 'store'])->name('fridges.store');
    Route::get('/fridges/{fridge}', [FridgeController::class, 'show'])->name('fridges.show');
    Route::get('/fridges/{fridge}/history', [FridgeController::class, 'history'])->name('fridges.history');
    Route::get('/fridges/{fridge}/restock', RestockController::class)->name('fridges.restock');

    Route::post('/fridges/{fridge}/items', [ItemController::class, 'store'])->name('items.store');
    Route::post('/items/{item}/use', [ItemController::class, 'markUsed'])->name('items.use');
    Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');

    Route::post('/fridges/{fridge}/shares', [ShareController::class, 'store'])->name('shares.store');
    Route::post('/shares/{share}/respond', [ShareController::class, 'respond'])->name('shares.respond');
});
