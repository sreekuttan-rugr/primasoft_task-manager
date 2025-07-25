<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TaskApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminDashboardController;

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

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->group(function () {
   
    Route::get('/tasks', [TaskApiController::class, 'index']);

    Route::post('/tasks', [TaskApiController::class, 'store']);
    
    Route::get('/tasks/{id}', [TaskApiController::class, 'show']);
    
    Route::put('/tasks/{id}', [TaskApiController::class, 'update']);
    Route::patch('/tasks/{id}', [TaskApiController::class, 'update']);
    
    Route::delete('/tasks/{id}', [TaskApiController::class, 'destroy']);
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index']);
});
