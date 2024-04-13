<?php


use App\Http\Controllers\Api\v1\ArticlesController;
use App\Http\Controllers\Api\v1\HomeController;
use App\Http\Controllers\Api\v1\PaymentController;
use App\Http\Controllers\Api\v1\Product\BrandController;
use App\Http\Controllers\Api\v1\Product\FeatureController;
use App\Http\Controllers\Api\v1\Product\ProductCategoryController;
use App\Http\Controllers\Api\v1\Product\ProductController;
use App\Http\Controllers\Api\v1\Product\ProductSubCategoryController;
use App\Http\Controllers\Api\v1\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware('api')->group( function () {
    Route::get("/" , [HomeController::class , 'index']);

    Route::resource('/articles' , ArticlesController::class)->names('v1.articles');

    Route::controller(UserController::class)->group (function () {
        Route::get('/users' , 'index');
        Route::get('/users/{user}' , 'profile');
    });

    Route::get('/payments', [PaymentController::class , 'index']);

    Route::resource('/products/categories' , ProductCategoryController::class);
    Route::resource('/products/sub-categories' , ProductSubCategoryController::class);
    Route::resource('/products' , ProductController::class);

    Route::resource('/brands' , BrandController::class);
    Route::resource('/features' , FeatureController::class);
});

require __DIR__.'/auth.php';
