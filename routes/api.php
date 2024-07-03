<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\ModeloController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/brands', [BrandController::class, 'store']);
Route::post('/models', [ModeloController::class, 'store']);
Route::get('/cars', [CarController::class, 'index']);
Route::post('/cars', [CarController::class, 'store']);
Route::post('/users', [UserController::class, 'store']);
Route::post('/photos/create', [PhotoController::class, 'store']);
Route::get('/users/{user_id}/rentals', [UserController::class, 'getUserRentals']);