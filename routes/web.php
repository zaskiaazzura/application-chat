<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/chat', function () {
    return Inertia::render('Chat/Index', [
        'auth' => [
            'user' => auth()->user()
        ]
    ]);
})->middleware(['auth', 'verified'])->name('chat');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/conversations', [App\Http\Controllers\Api\ConversationController::class, 'index']);
    Route::post('/conversations', [App\Http\Controllers\Api\ConversationController::class, 'store']);
    Route::get('/conversations/{id}/messages', [App\Http\Controllers\Api\MessageController::class, 'index']);
    Route::post('/conversations/{id}/messages', [App\Http\Controllers\Api\MessageController::class, 'store']);
    Route::post('/typing', [App\Http\Controllers\Api\MessageController::class, 'typing']);
    Route::get('/users', function () {
        return \App\Models\User::where('id', '!=', auth()->id())
            ->select('id', 'name', 'is_online')
            ->get();
    });
    Route::post('/typing', function (Illuminate\Http\Request $request) {
        broadcast(new \App\Events\UserTyping(
            $request->conversation_id,
            auth()->id()
        ))->toOthers();
        return response()->json(['ok' => true]);
    });
});

require __DIR__.'/auth.php';
