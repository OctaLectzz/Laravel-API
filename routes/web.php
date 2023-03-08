<?php

use App\Http\Controllers\PasswodResetController;
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
// Route::get('/reset-password/{token}', [PasswodResetController::class, 'showResetForm'])->name('password.reset');
// Route::post('/reset-password', [PasswodResetController::class, 'reset'])->name('password.update');
