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




// Example
Route::middleware('auth:sanctum')->group( function () {
    Route::resource('products', ProductController::class);
});




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
    // Edit Post
    Route::put('/edit/{post}', 'update')->middleware('auth:sanctum');
    // Delete Post
    Route::delete('/delete/{post}', 'destroy')->middleware('auth:sanctum');
});
// Route::apiResource('posts', PostController::class);



// Comment
Route::prefix('comments')->controller(CommentController::class)->group(function () {
    //All Comments
    Route::get('/', 'index');
    //Show 1 Comment
    Route::get('/{id}', 'show');
    // Create Comment
    Route::post('/create', 'store')->middleware(['auth:sanctum', 'throttle:6,10']);
    // Edit Comment
    Route::put('/edit/{comment}', 'update')->middleware(['auth:sanctum', 'check.comment.ownership', 'throttle:6,10']);
    // Delete Comment
    Route::delete('/delete/{comment}', 'destroy')->middleware('auth:sanctum');
});
// Route::apiResource('comments', CommentController::class);










Route::middleware('auth:sanctum')->group(function () {



});