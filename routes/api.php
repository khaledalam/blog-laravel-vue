<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\SignupController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login',[SignupController::class, 'login']);
Route::post('/register',[SignupController::class, 'register']);

Route::group([
    'middleware' => ['auth:sanctum'],
], function () {
    Route::get('/users',[SignupController::class, 'index']);
    Route::get('/users/{user}',[SignupController::class, 'show']);
    Route::put('/users/{user}',[SignupController::class, 'update']);
    Route::delete('/users/{user}',[SignupController::class, 'destroy']);
});

Route::group([
    'middleware' => ['auth:sanctum'],
], function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });


    Route::post('/blogs/create', [BlogController::class, 'store']);
    Route::get('/blogs', [BlogController::class, 'index']);
    Route::get('/blogs/{blog}', [BlogController::class, 'show']);
    Route::post('/blogs/{blog}', [BlogController::class, 'update']);
    Route::delete('/blogs/{blog}', [BlogController::class, 'destroy']);
});

