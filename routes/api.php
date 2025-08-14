<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OAuthController;

/**
 * @OA\Info(
 *     title="PDEX API Documentation",
 *     version="1.0.0",
 *     description="Post-Secondary Data Exchange (PDEX) API endpoints for managing student profiles, institutional data, and ministry oversight.",
 *     @OA\Contact(
 *         email="admin@pdex.gov.bc.ca",
 *         name="PDEX Support"
 *     )
 * )
 * 
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="PDEX API Server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="oauth2",
 *     type="oauth2",
 *     description="OAuth 2.0 Client Credentials flow",
 *     @OA\Flow(
 *         flow="clientCredentials",
 *         tokenUrl="/api/oauth/token",
 *         scopes={
 *             "read": "Read access to resources",
 *             "write": "Write access to resources",
 *             "*": "Full access to all resources"
 *         }
 *     )
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Laravel Sanctum token authentication"
 * )
 */

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API Status endpoint
Route::get('/status', function () {
    return response()->json([
        'status' => 'online',
        'timestamp' => now()->toISOString(),
        'version' => '1.0.0'
    ]);
})->name('api.status');

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'database' => 'connected',
        'timestamp' => now()->toISOString()
    ]);
})->name('api.health');

// OAuth 2.0 Client Credentials endpoints
Route::prefix('oauth')->group(function () {
    Route::post('/token', [OAuthController::class, 'token']);
    Route::post('/introspect', [OAuthController::class, 'introspect'])->middleware('auth:sanctum');
    Route::post('/revoke', [OAuthController::class, 'revoke']);
});
