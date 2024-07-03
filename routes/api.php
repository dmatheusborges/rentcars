<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\ModeloController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\RentalController;
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

$group = (object) [
    'marcas' => [
        'prefix' => 'brands',
    ],
    'modelos' => [
        'prefix' => 'models',
    ],
    'carros' => [
        'prefix' => 'cars',
    ],
    'fotos' => [
        'prefix' => 'photos',
    ],
    'aluguel' => [
        'prefix' => 'rentals',
    ],
    'usuario' => [
        'prefix' => 'users',
    ],
];

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group($group->marcas, function () {
    Route::post('/', [BrandController::class, 'store']);
});

Route::group($group->modelos, function () {
    Route::post('/', [ModeloController::class, 'store']);
});

Route::group($group->carros, function () {
    Route::get('/', [CarController::class, 'index']);
    Route::post('/', [CarController::class, 'store']);
});

Route::group($group->fotos, function () {
    Route::post('/', [PhotoController::class, 'store']);
});

Route::group($group->aluguel, function () {
    Route::post('/', [RentalController::class, 'store']);
});

Route::group($group->usuario, function () {
    Route::post('/', [UserController::class, 'store']);
    Route::get('/{user_id}/rentals', [UserController::class, 'getUserRentals']);
});