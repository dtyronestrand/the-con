<?php

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ServerConnectionController;
use App\Http\Controllers\SyncController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

Route::post('/settings/factory-reset', function () {
    Schema::dropAllTables();
    Artisan::call('migrate', ['--force' => true]);
    session()->flush();

    return redirect('/')->with('status', 'Factory reset successful. All data has been cleared.');
});

Route::post('/sync', [SyncController::class, 'sync'])->name('sync.run');

Route::post('/server/connect', [ServerConnectionController::class, 'connect'])->name('server.connect');

Route::get('/', [\App\Http\Controllers\CategoryController::class, 'index'])->middleware('auth')->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');
Route::post('/tasks/store', [\App\Http\Controllers\TaskController::class, 'store'])->name('tasks.store');
Route::put('/tasks/{id}', [\App\Http\Controllers\TaskController::class, 'update'])->name('tasks.update');
Route::delete('/tasks/{id}', [\App\Http\Controllers\TaskController::class, 'destroy'])->name('tasks.destroy');
Route::get('/auth/outlook', [CalendarController::class, 'redirectToProvider'])->name('auth.outlook');
Route::get('/auth/outlook/callback', [CalendarController::class, 'handleProviderCallback'])->name('auth.outlook.callback');
Route::get('/auth/google', [\App\Http\Controllers\GoogleCalendarController::class, 'redirectToProvider'])->name('auth.google');
Route::get('/auth/google/callback', [\App\Http\Controllers\GoogleCalendarController::class, 'handleProviderCallback'])->name('auth.google.callback');
Route::post('/google/sync-tasks', [\App\Http\Controllers\GoogleCalendarController::class, 'syncEventsAsTasks'])->name('google.sync-tasks');
Route::post('/services', [\App\Http\Controllers\ServiceController::class, 'store'])->name('services.store');
Route::put('/services/{id}', [\App\Http\Controllers\ServiceController::class, 'update'])->name('services.update');
Route::delete('/services/{id}', [\App\Http\Controllers\ServiceController::class, 'destroy'])->name('services.destroy');
Route::post('/trigger-sync', function () {
    $exitCode = Artisan::call('sync:run');

    return response()->json(['success' => $exitCode === 0, 'message' => 'Sync attempt finished']);
});
Route::post('/notes', [\App\Http\Controllers\NoteController::class, 'store'])->name('notes.store');
Route::put('/notes/{note}', [\App\Http\Controllers\NoteController::class, 'update'])->name('notes.update');
Route::delete('/notes/{note}', [\App\Http\Controllers\NoteController::class, 'destroy'])->name('notes.destroy');
require __DIR__.'/settings.php';
