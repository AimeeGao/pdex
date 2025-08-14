<?php

use Illuminate\Support\Facades\Route;
use Modules\Student\Http\Controllers\Api\StudentApiController;

/**
 * Student API Routes
 * 
 * These routes are for API access to student profile data
 * All routes require authentication via Laravel Sanctum
 */

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    /**
     * Student Profile API endpoints
     * 
     * @group Students
     */
    Route::apiResource('students', StudentApiController::class)->names([
        'index' => 'api.students.index',
        'store' => 'api.students.store',
        'show' => 'api.students.show',
        'update' => 'api.students.update',
        'destroy' => 'api.students.destroy',
    ]);
});
