<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\CalendarController;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/auth/outlook', [CalendarController::class, 'redirectToProvider'])->name('auth.outlook');
Route::get('/auth/outlook/callback', [CalendarController::class, 'handleProviderCallback'])->name('auth.outlook.callback');

require __DIR__.'/settings.php';
