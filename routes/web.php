<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\FridgeController;
use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/fridges', [FridgeController::class, 'index'])->name('fridges.index');
    Route::post('/fridges', [FridgeController::class, 'store'])->name('fridges.store');
    Route::get('/fridges/{fridge}', [FridgeController::class, 'show'])->name('fridges.show');
    Route::get('/fridges/{fridge}/history', [FridgeController::class, 'history'])->name('fridges.history');

    Route::post('/fridges/{fridge}/items', [ItemController::class, 'store'])->name('items.store');
    Route::post('/items/{item}/use', [ItemController::class, 'markUsed'])->name('items.use');
    Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');

});
