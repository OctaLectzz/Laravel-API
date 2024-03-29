<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostLikeController;
use App\Http\Controllers\PostSaveController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\AuthenticationController;

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
Route::prefix('auth')->controller(AuthenticationController::class)->group(function(){
    // Register
    Route::post('register', 'register');
    // Login
    Route::post('/login', 'login');
    // Logout
    Route::get('logout', 'logout')->middleware('auth:sanctum');
    // Change Password
    Route::post('/forgot-password', 'forgotPassword');
    Route::post('/reset-password', 'reset');
});


// ----User---- //
Route::prefix('users')->controller(UserController::class)->group(function () {
    // All Users
    Route::get('/', 'index');
    // Show 1 User
    Route::get('/profile', 'show')->middleware('auth:sanctum');
    // Create User
    Route::post('/create', 'store')->middleware('auth:sanctum');
    // Edit User
    Route::put('/edit/{user}', 'update')->middleware('auth:sanctum');
    // Delete User
    Route::delete('/delete/{user}', 'destroy')->middleware('auth:sanctum');
});
// Profile
Route::put('/profile/edit', [UserController::class, 'profile'])->middleware('auth:sanctum');
// Route::apiResource('users', UserController::class);


// ----Post---- //
Route::prefix('posts')->group(function () {

    // Post
    Route::controller(PostController::class)->group(function () {
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

    // Like Post
    Route::controller(PostLikeController::class)->middleware('auth:sanctum')->group(function () {
        // Like Post
        Route::post('/{postId}/like', 'like');
        // Unlike Post
        Route::delete('/{postId}/like', 'unlike');
    });

    // Save Post
    Route::controller(PostSaveController::class)->middleware('auth:sanctum')->group(function () {
        // All Saved Posts
        Route::get('/postsave/posts', [PostSaveController::class, 'index'])->middleware('auth:sanctum');
        // Save Post
        Route::post('/{postId}/save', 'save');
        // Unsave Post
        Route::delete('/{postId}/save', 'unsave');
    });

});
// Route::apiResource('posts', PostController::class);


// ----Comment---- //
Route::controller(CommentController::class)->group(function () {

    // Create Comment
    Route::post('/posts/{postId}/comments/create', 'store')->middleware(['auth:sanctum']);
    // Reply Comment
    Route::post('/posts/{postId}/comments/{commentId}/reply', 'reply')->middleware(['auth:sanctum']);
    
    // Show Comment in Post
    Route::get('posts/{post}/comments', 'show');

    Route::prefix('comments')->group(function () {
        // All Comments
        Route::get('/', 'index');
        // Show 1 Comment
        Route::get('/{id}', 'show');
        // Edit Comment
        Route::put('/edit/{comment}', 'update')->middleware(['auth:sanctum', 'check.comment.ownership']);
        // Delete Comment
        Route::delete('/delete/{comment}', 'destroy')->middleware('auth:sanctum');
    });

});
// Route::apiResource('comments', CommentController::class);


// ----Tag---- //
Route::prefix('tags')->controller(TagController::class)->group(function () {
    // All Tags
    Route::get('/', 'index');
    // Show 1 Tag
    Route::get('/{id}', 'show');
    // Create Tag
    Route::post('/create', 'store')->middleware('auth:sanctum');
    // Edit Tag
    Route::put('/edit/{tag}', 'update')->middleware('auth:sanctum');
    // Delete Tag
    Route::delete('/delete/{tag}', 'destroy')->middleware('auth:sanctum');
});
// Route::apiResource('tags', TagController::class);
// Post Tag
Route::get('/tagpost', [TagController::class, 'getpost']);


// ----Category---- //
Route::prefix('categories')->controller(CategoryController::class)->group(function () {
    // All Categories
    Route::get('/', 'index');
    // Show 1 Category
    Route::get('/{id}', 'show');
    // Create Category
    Route::post('/create', 'store')->middleware('auth:sanctum');
    // Edit Category
    Route::put('/edit/{category}', 'update')->middleware('auth:sanctum');
    // Delete Category
    Route::delete('/delete/{category}', 'destroy')->middleware('auth:sanctum');
});
// Route::apiResource('categories', CategoryController::class);
// Post Category
Route::get('/categorypost', [CategoryController::class, 'getpost']);