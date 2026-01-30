<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\CalendarController;


Route::get('/', [\App\Http\Controllers\ServiceController::class, 'index'])->name('home');


Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/auth/outlook', [CalendarController::class, 'redirectToProvider'])->name('auth.outlook');
Route::get('/auth/outlook/callback', [CalendarController::class, 'handleProviderCallback'])->name('auth.outlook.callback');
Route::post('/services', [\App\Http\Controllers\ServiceController::class, 'store'])->name('services.store');
Route::put('/services/{id}', [\App\Http\Controllers\ServiceController::class, 'update'])->name('services.update');
Route::delete('/services/{id}', [\App\Http\Controllers\ServiceController::class, 'destroy'])->name('services.destroy');

require __DIR__.'/settings.php';
