<?php

use App\Http\Controllers\CommentReplyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\MediaCommentController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MediaLikeDislikeController;
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

    //API
    Route::get('personal/files', [MediaController::class, 'myUploads']);
    Route::get('files', [MediaController::class, 'index']);
    Route::post('file', [MediaController::class, 'store']);
    Route::patch('file/{media}', [MediaController::class, 'update']);
    Route::delete('file/{media}', [MediaController::class, 'delete']);


    // Comments
    Route::get('/{media}/comments', [MediaCommentController::class, 'index']);
    Route::post('/media/{media}/comments/enable', [MediaCommentController::class, 'enableComments']);
    Route::post('/media/{media}/comments/disable', [MediaCommentController::class, 'disableComments']);
    Route::post('/media/{media}/comments', [MediaCommentController::class, 'store']);
    Route::delete('/media/comments/{comment}', [MediaCommentController::class, 'destroy']);
    Route::post('/media/comments/{comment}/reply', [CommentReplyController::class, 'store']);
    Route::get('/media/comments/{comment}/reply', [CommentReplyController::class, 'index']);

    //Likes and dislikes
    Route::post('/media/{media}/like', [MediaLikeDislikeController::class, 'toggleLike']);
    Route::post('/media/{media}/dislike', [MediaLikeDislikeController::class, 'toggleDislike']);

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
