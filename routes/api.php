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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
    Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
    Route::post('/refresh', [\App\Http\Controllers\AuthController::class, 'refresh']);
    Route::get('/user-profile', [\App\Http\Controllers\AuthController::class, 'userProfile']);
    Route::post('/change-pass', [\App\Http\Controllers\AuthController::class, 'changePassWord']);
});
Route::group([
    'middleware' => 'auth:api',
    'prefix' => 'store'

], function () {
    Route::get('/', [\App\Http\Controllers\StoreController::class, 'getListStore']);
    Route::get('/{id}', [\App\Http\Controllers\StoreController::class, 'detailStore']);
    Route::put('/{id}', [\App\Http\Controllers\StoreController::class, 'updateStore']);
    Route::delete('/{id}', [\App\Http\Controllers\StoreController::class, 'deleteStore']);
    Route::post('/create', [\App\Http\Controllers\StoreController::class, 'createStore']);

    Route::get('/{id}/product/', [\App\Http\Controllers\ProductController::class, 'getListProduct']);
    Route::get('/{id}/product/{productId}', [\App\Http\Controllers\ProductController::class, 'detailProduct']);
    Route::put('/{id}/product/{productId}', [\App\Http\Controllers\ProductController::class, 'updateProduct']);
    Route::delete('/{id}/product/{productId}', [\App\Http\Controllers\ProductController::class, 'deleteProduct']);
    Route::post('/{id}/product/create', [\App\Http\Controllers\ProductController::class, 'createProduct']);
});
