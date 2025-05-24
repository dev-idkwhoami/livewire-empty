<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// Test route to verify User model relations
Route::get('/test-relations', function () {
    $user = \App\Models\User::first();

    if (! $user) {
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

    Route::get('settings/profile', \App\Livewire\Settings\Profile::class)->name('settings.profile');
    Route::get('settings/password', \App\Livewire\Settings\Password::class)->name('settings.password');
    Route::get('settings/appearance', \App\Livewire\Settings\Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';
