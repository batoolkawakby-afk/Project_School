<?php

use App\Http\Controllers\CounselerController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\userController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('manager')->group(function () {
        Route::post('/register', [ManagerController::class, 'registerManager']);
    }); 
        Route::prefix('counseler')->group(function () {
        Route::post('/register', [CounselerController::class, 'registerCounseler']);
    });
    Route::prefix('student')->group(function () {
        Route::post('/register', [StudentController::class, 'registerStudent']);
    });
    Route::prefix('teacher')->group(function () {
        Route::post('/register', [TeacherController::class, 'registerTeacher']);
    }); 

    Route::post('/login', [userController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [userController::class, 'logout']);
});