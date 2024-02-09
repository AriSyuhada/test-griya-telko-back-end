<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PackageController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/auth/login', [AuthController::class, 'login']);

// All Roles
Route::middleware(['auth:sanctum', 'roleCheck:admin,sales'])->group(function () {
    // Package Controller
    Route::get('/packages', [PackageController::class, 'index']);
    Route::get('/packages/{id}', [PackageController::class, 'show']);
});

// Admin Role
Route::middleware(['auth:sanctum', 'roleCheck:admin'])->group(function () {
    // Auth Controller
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::get('/users', [AuthController::class, 'index']);

    // Package Controller
    Route::post('/packages', [PackageController::class, 'store']);
    Route::put('/packages/{id}', [PackageController::class, 'update']);
    Route::delete('/packages/{id}', [PackageController::class, 'destroy']);
});

// Sales Role
Route::middleware(['auth:sanctum', 'roleCheck:sales'])->group(function () {
    Route::put('/users/{id}', [AuthController::class, 'update']);
});