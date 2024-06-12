<?php

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

Route::get('/data',[ProductsController::class,'getAllProductsAndCategories']);
Route::get('/product/{id}',[ProductsController::class,'getProductById']);
Route::post('/user/code', [UserController::class, 'createOrUpdateUserWithVerifyCode']);
Route::post('/user/code/check', [UserController::class, 'verifyUserAndUpdateVerifyCode']);
Route::middleware(CheckToken::class)->group(function () {
    // Маршрут /auth
    Route::get('/auth', function () {
        return response()->json(['message' => 'Authenticated']);
    });
    // Вложенные маршруты, доступные после успешной аутентификации
    Route::get('/auth/user', [UserController::class, 'getUserData']);
    Route::get('/auth/user/cart', [UserController::class, 'getUserCart']);
    Route::get('/auth/user/favorite', [UserController::class, 'getUserFavorite']);
});
