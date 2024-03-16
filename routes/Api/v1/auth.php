<?php

use App\Http\Controllers\Api\v1\Auth\AuthController;
use Illuminate\Support\Facades\Route;

/*
 *
 * In this section implements auth system
 *
 */


Route::controller(AuthController::class)->middleware('api')->prefix('v1')->group(function (){
    Route::post('/register' , 'register');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout');

    Route::get('/user' , 'getCurrentUser');
});
