<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\DatabaseMigrationController;
use App\Http\Controllers\DeployWebhookController;
use App\Http\Controllers\IotPortalController;
use App\Http\Controllers\RfidController;

// Public API routes
Route::post('/login', [ApiController::class, 'login']);
Route::post('/register', [ApiController::class, 'register']);
Route::get('/public-data', [ApiController::class, 'getPublicData']);
Route::get('/database/status', [DatabaseMigrationController::class, 'apiStatus']);
Route::match(['get', 'post'], '/deploy-webhook', [DeployWebhookController::class, 'deploy']);

// RFID / e-KTP Device API (ESP32 Kelompok - Bebas CSRF)
Route::prefix('rfid')->group(function () {
    Route::post('/tap', [RfidController::class, 'tap']);
    Route::post('/heartbeat', [RfidController::class, 'heartbeat']);
});

// IoT RFID / NFC Portal Gate Routes (ESP32 Hardware)
Route::prefix('iot/portal')->group(function () {
    Route::post('/scan', [IotPortalController::class, 'apiScan']);
    Route::post('/ping', [IotPortalController::class, 'apiPing']);
    Route::get('/status', [IotPortalController::class, 'apiStatus']);
});

// Authenticated API routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [ApiController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        $user = $request->user();
        return response()->json([
            'status' => 'success',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'role_name' => $user->role_name,
                'role_label' => $user->role_label,
            ]
        ]);
    });
});
