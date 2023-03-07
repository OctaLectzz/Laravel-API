<?php

use App\Http\Controllers\AuthenticationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



// Reset Password
Route::get('/reset-password/{token}', [AuthenticationController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthenticationController::class, 'reset'])->name('password.update');
