<?php


use App\Http\Controllers\Api\v1\ArticlesController;
use App\Http\Controllers\Api\v1\HomeController;
use App\Http\Controllers\Api\v1\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('api')->group( function () {
    Route::get("/" , [HomeController::class , 'index']);

//    Route::controller(ArticlesController::class)->group(function () {
//        Route::get('/articles/{article}', 'show');
//        Route::get('/articles', 'index');
//        Route::post('/articles', 'store')->middleware("auth:api");
//    });

    Route::resource('/articles' , ArticlesController::class);
    
    Route::controller(UserController::class)->group (function () {
        Route::get('/users' , 'index');
        Route::get('/users/{user}' , 'profile');
    });
});


require __DIR__.'/auth.php';
