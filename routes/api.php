<?php

use Illuminate\Support\Facades\Route;
use Osem\Commenist\Http\Controllers\CommentsController;
use Osem\Commenist\Http\Controllers\VoteController;

if (config('comments.route.root') !== null) {
    Route::group(['prefix' => config('comments.route.root')], static function () {
        Route::group(['prefix' => config('comments.route.group'), 'as' => 'comments.',], static function () {
            Route::get('/', [CommentsController::class, 'get'])->name('get');
            Route::post('/', [CommentsController::class, 'store'])->name('store');
            Route::delete('/{comment}', [CommentsController::class, 'destroy'])->name('delete');
            Route::patch('/{comment}', [CommentsController::class, 'update'])->name('update');
            Route::get('/{comment}', [CommentsController::class, 'show'])->name('show')->where('comment', '[0-9]+');
            Route::post('/{comment}', [CommentsController::class, 'reply'])->name('reply');
            Route::post('/{comment}/vote', [VoteController::class, 'vote'])->name('vote');
        });
    });
}


