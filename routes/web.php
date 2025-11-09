<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\WalletConnectController;
use App\Http\Controllers\SmartContractController;
use App\Http\Controllers\ViewOnlyWalletController;
use Illuminate\Support\Facades\Route;

// Redirect to home if authenticated, otherwise to login
Route::get('/', function () {
    return auth()->check() ? redirect()->route('home') : redirect()->route('login');
});

// Dashboard - redirect to home
Route::get('/dashboard', function () {
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

// Home page
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});

// Routes pour les wallets (nécessite authentification)
Route::middleware(['auth'])->group(function () {
    Route::resource('wallets', WalletController::class);
    Route::get('wallets-import', [WalletController::class, 'import'])->name('wallets.import');
    Route::post('wallets-import', [WalletController::class, 'storeImport'])->name('wallets.store-import');
    Route::post('wallets/{wallet}/refresh-balance', [WalletController::class, 'refreshBalance'])->name('wallets.refresh-balance');
    Route::get('wallets/{wallet}/export-private-key', [WalletController::class, 'exportPrivateKey'])->name('wallets.export-private-key');
});

// Routes pour les wallets view-only (nécessite authentification)
Route::middleware(['auth'])->group(function () {
    Route::resource('view-only-wallets', ViewOnlyWalletController::class)->except(['create', 'edit']);
    Route::post('view-only-wallets/{viewOnlyWallet}/refresh-balance', [ViewOnlyWalletController::class, 'refreshBalance'])->name('view-only-wallets.refresh-balance');
    Route::post('view-only-wallets/{viewOnlyWallet}/refresh-statistics', [ViewOnlyWalletController::class, 'refreshStatistics'])->name('view-only-wallets.refresh-statistics');
});

// Routes pour les smart contracts (nécessite authentification)
Route::middleware(['auth'])->group(function () {
    Route::resource('contracts', SmartContractController::class);
});

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
