<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TimerCardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.register');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [TimerCardController::class, 'index'])->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Timer card routes
    Route::get('/dashboard', [TimerCardController::class, 'index']);

    // Route untuk menambah data timer card
    Route::post('/timer-cards', [TimerCardController::class, 'store']);

    // Route untuk memulai timer card dan menyimpan waktu serta customer
    Route::post('/timer-cards/{id}/start', [TimerCardController::class, 'start']);

    // Route untuk menambah sesi waktu pada timer card
    Route::post('/timer-cards/{id}/add-session', [TimerCardController::class, 'addSession']);
});

require __DIR__.'/auth.php';
