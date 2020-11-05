<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use app\Http\Middleware\adminOnly;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::middleware('auth:sanctum')->get('/user', [UserController::class, 'dashboard']);
Route::middleware('auth:sanctum','adminOnly')->get('/admin', [AdminController::class, 'index']);
Route::middleware('auth:sanctum','adminOnly')->get('/admin/posts', [AdminController::class, 'posts']);