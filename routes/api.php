<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ResetPasswordController;

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


// Authentication
Route::controller(AuthenticationController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('/login', 'login');
    Route::get('logout', 'logout')->middleware('auth:sanctum');
    Route::post('/forgot-password', 'forgotPassword')->middleware('auth:sanctum');
    Route::post('/reset-password', 'reset');
});


// Example
Route::middleware('auth:sanctum')->group( function () {
    Route::resource('products', ProductController::class);
});


// User
Route::apiResource('users', UserController::class);


// Fitur
Route::middleware('auth:sanctum')->group(function () {

    // Post
    Route::apiResource('posts', PostController::class);

    // Comment
    Route::apiResource('comments', CommentController::class);

});