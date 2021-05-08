<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', [\App\Http\Controllers\Usuario::class, 'register']);
Route::post('login', [\App\Http\Controllers\Usuario::class, 'login']);
Route::post('upload', [\App\Http\Controllers\FoodsController::class, 'upload']);
Route::get('getfile/{id}', [\App\Http\Controllers\FileController::class, 'getById']);

Route::middleware('auth:sanctum')->group(function (){
    Route::post('logout', [\App\Http\Controllers\Usuario::class, 'logout']);
   Route::get('getfoods', [\App\Http\Controllers\FoodsController::class, 'show']);
   Route::post('addfoods', [\App\Http\Controllers\FoodsController::class, 'store']);
   Route::post('updatefoods', [\App\Http\Controllers\FoodsController::class, 'update']);
   Route::post('deletefoods', [\App\Http\Controllers\FoodsController::class, 'delete']);

    Route::post('addorder', [\App\Http\Controllers\OrderController::class, 'store']);
    Route::post('showorder', [\App\Http\Controllers\OrderController::class, 'show']);
    Route::post('order_food_delete', [\App\Http\Controllers\OrderController::class, 'deletefoodoforder']);
    Route::post('order_food_add', [\App\Http\Controllers\OrderController::class, 'addfoodsorder']);
});
