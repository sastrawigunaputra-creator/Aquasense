<?php

use App\Http\Controllers\Api\DeviceController;
use App\Http\Controllers\Api\WaterLogController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\AppController;
use Illuminate\Support\Facades\Route;

Route::post('/devices', [DeviceController::class, 'store']);
Route::post('/water-logs', [WaterLogController::class, 'store']);
Route::get('/notifications', [NotificationController::class, 'index']);
Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);
Route::delete('/devices/{id}', [DeviceController::class, 'destroy']);
Route::prefix('app')->group(function () {
    Route::get('/latest-data/{device_id}', [AppController::class, 'getLatestData']);
    Route::get('/notifications/{device_id}', [AppController::class, 'getNotifications']);
    Route::put('/settings/{device_id}', [AppController::class, 'updateSettings']);
});
