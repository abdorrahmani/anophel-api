<?php


use App\Http\Controllers\Api\v1\ArticlesController;
use App\Http\Controllers\Api\v1\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group( function () {
    Route::controller(ArticlesController::class)->group(function () {
        Route::get('/articles/{article}', 'show');
        Route::get('/articles', 'index');
    });

    Route::controller(UserController::class)->group (function () {
        Route::get('/users' , 'index');
        Route::get('/users/{user}' , 'profile');
    });
});

