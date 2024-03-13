<?php


use App\Http\Controllers\Api\v1\ArticlesController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group( function () {
    Route::get("/articles" , [ArticlesController::class , 'index']);
});

