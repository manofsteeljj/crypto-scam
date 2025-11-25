<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TradingController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\MarketController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SecurityController;
use App\Http\Controllers\KycController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication Routes (No validation as per requirements)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.update');

// Protected Routes (Require Authentication)
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Trading
    Route::get('/trading/spot', [TradingController::class, 'spot'])->name('trading.spot');
    Route::get('/trading/futures', [TradingController::class, 'futures'])->name('trading.futures');
    Route::post('/trading/buy', [TradingController::class, 'buy'])->name('trading.buy');
    Route::post('/trading/sell', [TradingController::class, 'sell'])->name('trading.sell');
    
    // Markets
    Route::get('/markets', [MarketController::class, 'index'])->name('markets');
    Route::get('/markets/{symbol}', [MarketController::class, 'show'])->name('markets.show');
    
    // Wallet
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet');
    Route::get('/wallet/deposit', [WalletController::class, 'showDepositForm'])->name('wallet.deposit');
    Route::post('/wallet/deposit', [WalletController::class, 'deposit'])->name('wallet.deposit.submit');
    Route::get('/wallet/withdraw', [WalletController::class, 'showWithdrawForm'])->name('wallet.withdraw');
    Route::post('/wallet/withdraw', [WalletController::class, 'withdraw'])->name('wallet.withdraw.submit');
    Route::get('/wallet/history', [WalletController::class, 'history'])->name('wallet.history');
    
    // Transactions
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions');
    Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('transactions.show');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');
    
    // Security
    Route::get('/security', [SecurityController::class, 'index'])->name('security');
    Route::post('/security/password', [SecurityController::class, 'updatePassword'])->name('security.password');
    Route::post('/security/2fa/enable', [SecurityController::class, 'enable2FA'])->name('security.2fa.enable');
    Route::post('/security/2fa/disable', [SecurityController::class, 'disable2FA'])->name('security.2fa.disable');
    Route::get('/security/devices', [SecurityController::class, 'devices'])->name('security.devices');
    Route::delete('/security/devices/{id}', [SecurityController::class, 'removeDevice'])->name('security.devices.remove');
    Route::get('/security/activity', [SecurityController::class, 'activity'])->name('security.activity');
    
    // API Keys
    Route::get('/security/api-keys', [SecurityController::class, 'apiKeys'])->name('security.api-keys');
    Route::post('/security/api-keys', [SecurityController::class, 'createApiKey'])->name('security.api-keys.create');
    Route::delete('/security/api-keys/{id}', [SecurityController::class, 'deleteApiKey'])->name('security.api-keys.delete');
    
    // KYC Verification
    Route::get('/kyc', [KycController::class, 'index'])->name('kyc');
    Route::post('/kyc/basic', [KycController::class, 'submitBasic'])->name('kyc.basic');
    Route::post('/kyc/documents', [KycController::class, 'submitDocuments'])->name('kyc.documents');
    Route::post('/kyc/address', [KycController::class, 'submitAddress'])->name('kyc.address');
    Route::get('/kyc/status', [KycController::class, 'status'])->name('kyc.status');
    
    // Notifications
    Route::get('/notifications', function () {
        return view('notifications.index');
    })->name('notifications');
    
    // Settings
    Route::get('/settings', function () {
        return view('settings.index');
    })->name('settings');
    
    // Help & Support
    Route::get('/support', function () {
        return view('support.index');
    })->name('support');
});

// Error Pages
Route::fallback(function () {
    return view('errors.404');
});
