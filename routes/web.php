<?php

use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\GatewayController;
use App\Http\Controllers\IndividualController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Public routes
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Support both GET and POST for logout for compatibility
Route::match(['GET', 'POST'], '/logout', [AuthController::class, 'logout'])->name('logout');

// FSG-style authentication routes - following exact FSG pattern
Route::middleware('guest')->group(function () {
    // Route::get('/portal-login', [AuthController::class, 'portalLogin'])->name('portalLogin');
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/idir-login', [AuthController::class, 'idirLogin'])->name('idir-login');
    Route::get('/bceid-login', [AuthController::class, 'bceidLogin'])->name('bceid-login');
    Route::get('/bcsc-login', [AuthController::class, 'bcscLogin'])->name('bcsc-login');
    

    // Handle SSO callback
    // Route::get('/callback', [AuthController::class, 'callback'])->name('callback');
    
    // Admin login routes
    Route::get('admin/login', [AdminAuthController::class, 'login'])->name('admin.login');
    Route::get('admin/applogin', [AdminAuthController::class, 'redirectToKeycloak'])->name('admin.callback');
    Route::get('admin/auth', [AdminAuthController::class, 'redirectToKeycloak'])
        ->defaults('idp', 'idir')
        ->name('admin.auth');
});

// Protected routes
Route::middleware(['auth'])->group(function () {
    
    // Gateway routes for external applications
    Route::get('gateway/{application}', [GatewayController::class, 'showRedirectForm'])->name('gateway.redirect');
    Route::post('gateway/{application}', [GatewayController::class, 'redirectToApplication'])->name('gateway.post');
});

Route::post('test-gateway/{application}', [GatewayController::class, 'testRedirectToApplication'])->name('test-gateway.redirect');

// Admin logout route
Route::middleware(['auth'])->group(function () {
    Route::post('admin/logout', [AdminAuthController::class, 'adminLogout'])->name('admin.logout');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
