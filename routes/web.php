<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function() {
    return Inertia::render('Index', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function() {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function() {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

Route::get('/events', [EventController::class, 'index'])
    ->name('event.index');

Route::get('/events/{category_slug?}/{category_id}', [
    EventController::class,
    'index',
])
    ->name('event.index');

Route::get('/eventsload', [EventController::class, 'eventsLoad'])
    ->name('event.load');

Route::get('/event/{title}/{id}', [EventController::class, 'show'])
    ->name('event.show');

require __DIR__ . '/auth.php';
