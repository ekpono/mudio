<?php

use App\Http\Controllers\CommentReplyController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FlagController;
use App\Http\Controllers\FlagTypeController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\MediaCommentController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\MediaLikeDislikeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TagController;
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

Route::get('/home', DashboardController::class)->name('home');
Route::get('/watch/{media}', [DashboardController::class, 'show']);
Route::get('files', [MediaController::class, 'index']);
Route::get('/{media}/comments', [MediaCommentController::class, 'index']);
Route::get('/media/comments/{comment}/reply', [CommentReplyController::class, 'index']);
Route::get('/', GuestController::class)->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/uploads', [DashboardController::class, 'uploads'])->name('my.uploads');

    //Media
    Route::get('personal/files', [MediaController::class, 'myUploads']);
    Route::post('file', [MediaController::class, 'store']);
    Route::patch('file/{media}', [MediaController::class, 'update']);
    Route::delete('file/{media}', [MediaController::class, 'delete']);

    // Comments
    Route::post('/media/{media}/comments', [MediaCommentController::class, 'store']);
    Route::delete('/media/comments/{comment}', [MediaCommentController::class, 'destroy']);
    Route::post('/media/comments/{comment}/reply', [CommentReplyController::class, 'store']);

    //Likes and dislikes
    Route::post('/media/{media}/like', [MediaLikeDislikeController::class, 'toggleLike']);
    Route::post('/media/{media}/dislike', [MediaLikeDislikeController::class, 'toggleDislike']);

    //Flag and Report
    Route::post('/media/{media}/flags', [FlagController::class, 'store']);
    Route::get('/media/{media}/flags', [FlagController::class, 'index']);
    Route::get('/media/flag/types', [FlagTypeController::class, 'index']);

    //Country
    Route::get('countries', [CountryController::class, 'index']);

    //Settings
    Route::post('settings', [SettingsController::class, 'store'])->name('settings.store');

    //Tags
    Route::get('tags', [TagController::class, 'index']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
