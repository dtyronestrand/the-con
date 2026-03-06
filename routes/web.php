<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ServerConnectionController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\SyncController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;

Route::post('/settings/factory-reset', function() {
    Schema::dropAllTables();
    Artisan::call('migrate', ['--force' => true]);
    session()->flush();
    return redirect('/')->with('status', 'Factory reset successful. All data has been cleared.');
});

Route::post('/sync', [SyncController::class, 'sync'])->name('sync.run');

Route::post('/server/connect', [ServerConnectionController::class, 'connect'])->name('server.connect');

Route::get('/', [\App\Http\Controllers\CategoryController::class, 'index'])->middleware('auth')->name('home');


Route::get('dashboard', [CalendarController::class, 'index'])->middleware('auth')->name('dashboard');


Route::get('/auth/outlook', [CalendarController::class, 'redirectToProvider'])->name('auth.outlook');
Route::get('/auth/outlook/callback', [CalendarController::class, 'handleProviderCallback'])->name('auth.outlook.callback');
Route::post('/services', [\App\Http\Controllers\ServiceController::class, 'store'])->name('services.store');
Route::put('/services/{id}', [\App\Http\Controllers\ServiceController::class, 'update'])->name('services.update');
Route::delete('/services/{id}', [\App\Http\Controllers\ServiceController::class, 'destroy'])->name('services.destroy');
Route::post('/trigger-sync', function(){
    $exitCode = Artisan::call('sync:run');
    return response()->json(['success' => $exitCode === 0, 'message' => 'Sync attempt finished']);
});
require __DIR__.'/settings.php';
