<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('auth:sanctum')->get('/posts', [PostController::class, 'index']);
Route::middleware('auth:sanctum')->post('/post', [PostController::class, 'viewPost']);
Route::middleware('auth:sanctum')->post('/delete/post', [PostController::class, 'destroy']);
Route::middleware('auth:sanctum')->post('/edit/post', [PostController::class, 'editPost']);
Route::middleware('auth:sanctum')->post('/create/post', [UserController::class, 'newPost']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']); 