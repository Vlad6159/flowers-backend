<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductsController;
use App\Http\Middleware\CheckToken;
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

Route::get('/products',[ProductsController::class,'getAllProductsAndCategories']);
Route::post('/user/code', [UserController::class, 'createOrUpdateUserWithVerifyCode']);
Route::post('/user/code/check', [UserController::class, 'verifyUserAndUpdateVerifyCode']);
Route::middleware(CheckToken::class)->group(function () {
    Route::get('/auth', function () {
        return response()->json(['message' => 'Authenticated']);
    });
    Route::get('/user', [UserController::class, 'getUserData']);
    Route::post('/user/update', [UserController::class, 'updateUserData']);
    Route::post('/user/order',[OrderController::class,'makeOrder']);
    Route::post('/user/order/history',[OrderController::class,'getUserOrderHistory']);
});
