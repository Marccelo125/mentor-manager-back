<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MentorController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/users', [UserController::class, 'index']);
Route::post('/register', [UserController::class, 'store']);

Route::post('/login', [AuthController::class, 'login']);
Route::delete('/logout/{id}', [AuthController::class, 'logout']);

Route::apiResource('/mentors', MentorController::class)->middleware(['auth:sanctum', 'admin']);

