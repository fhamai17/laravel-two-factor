<?php

use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\LoginController;
use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\UserAuthController;
use Illuminate\Auth\Events\Login;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/register', [RegisterController::class, 'store']);
// Route::post('/login',[UserAuthController::class,'login']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);
Route::get('/home', [HomeController::class, 'index'])->middleware(['2fa', 'throttle']);