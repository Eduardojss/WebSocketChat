<?php

use App\Http\Controllers\MessagesController;
use App\Http\Controllers\UserController;
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
Route::middleware([
    'auth:sanctum'
])->group(function () {
    Route::get('/users',[UserController::class, 'index']);
    Route::get('/users/{userId}',[UserController::class, 'activeUser']);
    Route::post('/users/isWriting',[UserController::class, 'isWriting']);
    Route::post('/messages/send',[MessagesController::class, 'sendMessage']);
    Route::get('/messages/{userId}',[MessagesController::class, 'messages']);
});



