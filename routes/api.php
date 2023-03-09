<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

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
    // Register
    Route::post('register', 'register');
    // Login
    Route::post('/login', 'login')->middleware('throttle:6,30');
    // Logout
    Route::get('logout', 'logout')->middleware('auth:sanctum');
    // Change Password
    Route::post('/forgot-password', 'forgotPassword')->middleware(['auth:sanctum', 'throttle:6,30']);
    Route::post('/reset-password', 'reset')->middleware('throttle:6,30');
});


// Example
Route::middleware('auth:sanctum')->group( function () {
    Route::resource('products', ProductController::class);
});


// User
Route::apiResource('users', UserController::class);


// ----Post---- //
Route::prefix('posts')->controller(PostController::class)->group(function () {
    //All Posts
    Route::get('/', 'index');
    //Show 1 Post
    Route::get('/{id}', 'show');
    // Create Post
    Route::post('/create', 'store')->middleware('auth:sanctum');
    // Update Post
    Route::put('/update/{post}', 'update')->middleware('auth:sanctum');
    // Delete Post
    Route::delete('/delete/{post}', 'destroy')->middleware('auth:sanctum');
});
// Route::apiResource('posts', PostController::class);


// Comment
Route::apiResource('comments', CommentController::class);




Route::middleware('auth:sanctum')->group(function () {



});