<?php


use App\Http\Controllers\Api\v1\ArticlesController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group( function () {
    Route::controller(ArticlesController::class)->group(function () {
        Route::get('/articles/{article}', 'show');
        Route::get('/articles', 'index');
    });
});

