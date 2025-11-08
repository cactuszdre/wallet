<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

// Page d'accueil
Route::middleware(['auth'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
});

// Routes pour les wallets (nécessite authentification)
Route::middleware(['auth'])->group(function () {
    Route::resource('wallets', WalletController::class);
    Route::post('wallets/{wallet}/refresh-balance', [WalletController::class, 'refreshBalance'])->name('wallets.refresh-balance');
    Route::get('wallets/{wallet}/export-private-key', [WalletController::class, 'exportPrivateKey'])->name('wallets.export-private-key');
});
