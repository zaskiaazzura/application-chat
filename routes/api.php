<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\UserController;

Route::middleware('auth:sanctum')->group(function () {

    Route::get(
        '/conversations',
        [ConversationController::class, 'index']
    );

    Route::post(
        '/conversations',
        [ConversationController::class, 'store']
    );

    Route::post(
        '/conversations/{id}/add-member',
        [ConversationController::class, 'addMember']
    );

    Route::delete(
        '/conversations/{id}/remove-member/{userId}',
        [ConversationController::class, 'removeMember']
    );

    Route::get(
        '/conversations/{id}/messages',
        [MessageController::class, 'index']
    );

    Route::post(
        '/conversations/{id}/messages',
        [MessageController::class, 'store']
    );

    Route::delete(
        '/messages/{id}',
        [MessageController::class, 'destroy']
    );

    Route::post(
        '/messages/{id}/read',
        [MessageController::class, 'markAsRead']
    );

    Route::get(
        '/notifications',
        [NotificationController::class, 'index']
    );

    Route::get(
        '/messages/search',
        [MessageController::class, 'search']
    );

    Route::get(
        '/users/search',
        [UserController::class, 'search']
    );
});