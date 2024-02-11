<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
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

// Route::middleware(['cors'])->group(function () {
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/customers/{id}/file/{file}', [CustomerController::class, 'file']);
// });

// All Roles
Route::middleware(['auth:sanctum', 'roleCheck:admin,sales'])->group(function () {
    // Package Controller
    Route::get('/packages', [PackageController::class, 'index']);
    Route::get('/packages/{id}', [PackageController::class, 'show']);

    // Customer Controller
    Route::get('/customers', [CustomerController::class, 'index']);
    Route::get('/customers/{id}', [CustomerController::class, 'show']);
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

    // Customer Controller
    Route::get('/customers/{id}/verify', [CustomerController::class, 'verif']);
});

// Sales Role
Route::middleware(['auth:sanctum', 'roleCheck:sales'])->group(function () {
    // Auth Controller
    Route::put('/users/{id}', [AuthController::class, 'update']);

    // Customer Controller
    Route::post('/customers', [CustomerController::class, 'store']);
    Route::put('/customers/{id}', [CustomerController::class, 'update']);
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy']);
});