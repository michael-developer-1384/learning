<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EventController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/events', [EventController::class, 'index'])->name('event.index');
    Route::get('/events/users', [EventController::class, 'exportUsers'])->name('event.export_users');
    Route::get('/events/import-results', [EventController::class, 'showImportResults'])->name('event.import_results');
    Route::post('/events/users', [EventController::class, 'importUsers'])->name('event.import_users');
});
