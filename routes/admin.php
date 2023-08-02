<?php

use App\Http\Controllers\Admin\AdminPagesController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\FlagController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

Route::get('admin-login', [AuthenticatedSessionController::class, 'create'])
    ->name('admin.index.login');

Route::post('admin-login', [AuthenticatedSessionController::class, 'store']);

//Pages
Route::middleware(['is_admin', 'auth', 'verified'])->domain('admin.' . config('app.url'))->group(function () {

    Route::get('/admin-dashboard', [AdminPagesController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/admin-media', [AdminPagesController::class, 'media']);

    Route::get('/admin-report', [AdminPagesController::class, 'flag']);

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('admin.logout');

});


//API ENDPOINTS
Route::middleware(['auth', 'verified'])->prefix('api/admin')->group(function () {
    Route::resource('users', UserController::class);
    Route::get('media', [MediaController::class, 'index']);
    Route::get('report', [FlagController::class, 'index']);
});
