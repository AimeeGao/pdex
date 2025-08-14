<?php

use App\Http\Controllers\InstitutionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Institution;
/*
|--------------------------------------------------------------------------
| Institution API Routes
|--------------------------------------------------------------------------
|
| Here are the API routes for managing institutions. These routes are
| prefixed with 'api' and can be used by frontend applications or
| external systems to interact with institution data.
|
*/

Route::middleware(['auth'])->group(function () {
    // RESTful resource routes for institutions (authorization handled by policy)
    Route::apiResource('institutions', InstitutionController::class);
    
    // Additional custom endpoints
    Route::get('institutions/stats/summary', [InstitutionController::class, 'stats'])
         ->name('institutions.stats');
    
    Route::get('institutions/active/list', [InstitutionController::class, 'active'])
         ->name('institutions.active');
    
    Route::get('institutions/search/query', [InstitutionController::class, 'search'])
         ->name('institutions.search');
});

// Public routes (if needed for external integrations)
Route::middleware(['throttle:60,1'])->group(function () {
    // Public institution directory (only active, public institutions)
    Route::get('public/institutions', function (Request $request) {
        $institutions = Institution::active()
            ->public()
            ->inGoodStanding()
            ->select([
                'guid',
                'legal_operating_name',
                'operating_name',
                'institution_type',
                'website',
                'city',
                'province_state',
                'economic_region'
            ])
            ->orderBy('legal_operating_name')
            ->paginate(50);
        
        return response()->json($institutions);
    })->name('institutions.public');
});
