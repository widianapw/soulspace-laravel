<?php

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


Route::post("/login", [\App\Http\Controllers\Api\AuthController::class, "login"]);

Route::post("/register", [\App\Http\Controllers\Api\AuthController::class, "register"]);

Route::middleware(["auth:sanctum"])->group(function () {
    Route::get('/psychologist', [\App\Http\Controllers\Api\PsychologistController::class, "get"])->name("psychologist.get");

    Route::post('/chat', [\App\Http\Controllers\Api\ChatController::class, "postChat"])->name("chat.post");
    Route::get('/chat', [\App\Http\Controllers\Api\ChatController::class, "get"])->name("chat.get");
});

