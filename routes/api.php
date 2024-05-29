<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdviserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ChatMessageController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\ReviewerController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

Broadcast::routes(['middleware' => ['auth:sanctum']]);

Route::prefix('auth')
    ->as('auth.')
    ->group(function () {
        Route::post('login', [AuthController::class, 'login'])->name('login');
        Route::post('register', [AuthController::class, 'register'])->name('register');
        Route::post('login_with_token', [AuthController::class, 'loginWithToken'])
            ->middleware('auth:sanctum')
            ->name('login_with_token');
        Route::get('logout', [AuthController::class, 'logout'])
            ->middleware('auth:sanctum')
            ->name('logout');
    });


Route::middleware('auth:sanctum')
    ->group(function () {
        Route::apiResource('chat', ChatController::class)->only(['index', 'store', 'show']);
        Route::apiResource('chat_message', ChatMessageController::class)->only(['index', 'store']);
        Route::apiResource('user', UserController::class)->only(['index']);
        Route::apiResource('adviser', AdviserController::class)->only(['index', 'show']);
        Route::get('adviser/search', [UserController::class, 'search'])->name('adviser.search');
        Route::get('/check-database-connection', [DatabaseController::class, 'checkConnection']);

        // Route::apiResource('admin', AdminController::class);
        // Route::apiResource('client', ClientController::class);
        // Route::apiResource('Reviewer', ReviewerController::class);
    });
