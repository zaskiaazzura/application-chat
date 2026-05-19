<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use Inertia\Inertia;

Broadcast::routes(['middleware' => ['web', 'auth']]);

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('chat');
    }
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', function () {
    return redirect()->route('chat');
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
