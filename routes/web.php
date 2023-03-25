<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', GuestController::class)->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/home', DashboardController::class)->name('home');
    Route::get('/uploads', [DashboardController::class, 'uploads'])->name('my.uploads');
    Route::get('/watch/{media}', [DashboardController::class, 'show']);

    //API
    Route::get('personal/files', [MediaController::class, 'myUploads']);
    Route::get('files', [MediaController::class, 'index']);
    Route::post('file', [MediaController::class, 'store']);
    Route::patch('file/{media}', [MediaController::class, 'update']);
    Route::delete('file/{media}', [MediaController::class, 'delete']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
