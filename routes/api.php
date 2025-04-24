<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NetMindController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

// for the temprary  "c43e5d81612e45eca3bd501a787a38ec"

Route::post('/login', [AuthController::class, 'login']);

Route::post('/register', [AuthController::class, 'register']);

Route::middleware(['api', 'web'])->post('/chat', [NetMindController::class, 'chat']);



// Route::post('/chat', [NetMindController::class, 'chat']);
Route::middleware('auth:api')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
