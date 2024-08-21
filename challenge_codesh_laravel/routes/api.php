<?php

use App\Http\Controllers\FoodFactController;
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

Route::get('/products', [FoodFactController::class, 'index']);
Route::get('/products/{code}', [FoodFactController::class, 'show']);
Route::put('/products/{code}', [FoodFactController::class, 'update']);
Route::delete('/products/{code}', [FoodFactController::class, 'destroy']);
