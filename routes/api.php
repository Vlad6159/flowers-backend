<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductsController;
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
Route::get('/user',[UserController::class,'getUserData']);

Route::post('/user/code', [UserController::class, 'createOrUpdateUserWithVerifyCode']);
Route::post('/user/code/check', [UserController::class, 'verifyUserAndUpdateVerifyCode']);
Route::middleware('checkToken')->get('/api/auth', function (Request $request) {
    Route::get('/data',[UserController::class,'getUserData']);
});
