<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TimerCardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.register'); // Tampilan halaman pendaftaran default
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard route (index dari TimerCardController)
    Route::get('/dashboard', [TimerCardController::class, 'index'])->name('dashboard');

    // Profile routes (untuk user profile management)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Timer card routes
    // Menampilkan semua timer cards di halaman dashboard
    Route::get('/timer-cards', [TimerCardController::class, 'index'])->name('timer-cards.index');

    // Menyimpan timer card baru
    Route::post('/timer-cards', [TimerCardController::class, 'store'])->name('timer-cards.store');

    // Memulai timer card dan menyimpan customer serta waktu
    Route::post('/timer-cards/{id}/start', [TimerCardController::class, 'start'])->name('timer-cards.start');

    // Menambah sesi waktu pada timer card
    Route::post('/timer-cards/{id}/add-session', [TimerCardController::class, 'addSession'])->name('timer-cards.add-session');

    // Mengupdate informasi timer card
    Route::patch('/timer-cards/{id}', [TimerCardController::class, 'update'])->name('timer-cards.update');

    // Menghapus timer card
    Route::delete('/timer-cards/{id}', [TimerCardController::class, 'destroy'])->name('timer-cards.destroy');
});

require __DIR__.'/auth.php';
