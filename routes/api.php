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




// ----Example Laravel Sanctum---- //
Route::middleware('auth:sanctum')->group( function () {
    Route::resource('products', ProductController::class);
});




// ----Authenticate---- //
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


// ----User---- //
Route::prefix('users')->controller(UserController::class)->group(function () {
    // All Users
    Route::get('/', 'index');
    // Show 1 User
    Route::get('/{id}', 'show');
    // Create User
    Route::post('/create', 'store')->middleware('auth:sanctum');
    // Edit User
    Route::put('/edit/{user}', 'update')->middleware('auth:sanctum');
    // Delete User
    Route::delete('/delete/{user}', 'destroy')->middleware('auth:sanctum');
});
// Route::apiResource('users', UserController::class);


// ----Post---- //
Route::prefix('posts')->controller(PostController::class)->group(function () {
    // All Posts
    Route::get('/', 'index');
    // Show 1 Post
    Route::get('/{id}', 'show');
    // Create Post
    Route::post('/create', 'store')->middleware('auth:sanctum');
    // Edit Post
    Route::put('/edit/{post}', 'update')->middleware('auth:sanctum');
    // Delete Post
    Route::delete('/delete/{post}', 'destroy')->middleware('auth:sanctum');
});
// Route::apiResource('posts', PostController::class);


// ----Comment---- //
Route::controller(CommentController::class)->group(function () {

    // Create Comment
    Route::post('/posts/{postId}/comments/create', 'store')->middleware(['auth:sanctum', 'throttle:6,10']);
    
    // Show Comment in Post
    Route::get('posts/{post}/comments', 'show');

    Route::prefix('comments')->group(function () {
        // All Comments
        Route::get('/', 'index');
        // Show 1 Comment
        Route::get('/{id}', 'show');
        // Edit Comment
        Route::put('/edit/{comment}', 'update')->middleware(['auth:sanctum', 'check.comment.ownership', 'throttle:6,10']);
        // Delete Comment
        Route::delete('/delete/{comment}', 'destroy')->middleware('auth:sanctum');
    });

});
// Route::apiResource('comments', CommentController::class);