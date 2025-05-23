<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Test route to verify User model relations
Route::get('/test-relations', function () {
    $user = \App\Models\User::first();

    if (!$user) {
        return response()->json(['error' => 'No users found'], 404);
    }

    return response()->json([
        'user' => $user->name,
        'posts_count' => $user->posts->count(),
        'comments_count' => $user->comments->count(),
        'posts' => $user->posts->map(function ($post) {
            return [
                'id' => $post->id,
                'title' => $post->title,
                'comments' => $post->comments->map(function ($comment) {
                    return [
                        'id' => $comment->id,
                        'content' => $comment->content,
                    ];
                }),
            ];
        }),
    ]);
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
