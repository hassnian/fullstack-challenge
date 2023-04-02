<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DatasourceController;
use App\Http\Controllers\UserPreferenceController;
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

Route::group([ 'middleware' => 'api', 'prefix' => 'auth'], function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::put('/logout', [AuthController::class, 'logout']);
    Route::put('/refresh', [AuthController::class, 'refresh']);
    Route::get('/me', [AuthController::class, 'me']);
});

Route::group([ 'middleware' => 'api'], function () {
    Route::get('/authors', [AuthorController::class, 'index']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/datasources', [DatasourceController::class, 'index']);
    Route::get('/articles', [ArticleController::class, 'index']);
});

Route::group([ 'middleware' => 'auth:api',], function () {
    Route::get('/user/preferences', [UserPreferenceController::class, 'index']);
    Route::put('/user/preferences', [UserPreferenceController::class, 'update']);
    Route::get('/user/feed', [UserPreferenceController::class, 'getUserFeed']);

});




