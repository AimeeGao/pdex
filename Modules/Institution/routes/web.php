<?php

use Illuminate\Support\Facades\Route;
use Modules\Institution\Http\Controllers\InstitutionController;

Route::prefix('institution')->group(function () {
    Route::group([
        'middleware' => ['auth', 'institution_active'],
        'as' => 'institution.',
    ], function () {
        Route::get('/', [InstitutionController::class, 'index'])->name('dashboard');
        Route::get('applications', [InstitutionController::class, 'applications'])->name('applications.index');
        
        // Admin-only institution routes
        Route::group([
            'middleware' => ['institution_admin'],
        ], function () {
            Route::get('admin', [InstitutionController::class, 'admin'])->name('admin.index');
            Route::get('settings', [InstitutionController::class, 'settings'])->name('settings.index');
            Route::put('settings', [InstitutionController::class, 'updateSettings'])->name('settings.update');
        });
    });
});
