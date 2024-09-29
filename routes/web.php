<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TimerCardController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ActiveTherapistController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Default registration page route
Route::get('/', function () {
    return view('auth.register');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard route
    Route::get('/dashboard', [TimerCardController::class, 'index'])->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Timer card routes
    Route::get('/timer-cards', [TimerCardController::class, 'index'])->name('timer-cards.index');
    Route::post('/timer-cards', [TimerCardController::class, 'store'])->name('timer-cards.store');
    Route::post('/timer-cards/{id}/start', [TimerCardController::class, 'start'])->name('timer-cards.start');
    Route::post('/timer-cards/{id}/add-session', [TimerCardController::class, 'addSession'])->name('timer-cards.addSession');
    Route::patch('/timer-cards/{id}', [TimerCardController::class, 'update'])->name('timer-cards.update');
    Route::delete('/timer-cards/{id}', [TimerCardController::class, 'destroy'])->name('timer-cards.destroy');
    


    // Master
    Route::get('/master', [MasterController::class, 'index'])->name('master');
    // Sub nav export data
    Route::get('/export-data', [ExportController::class, 'show'])->name('export-data');
    Route::get('/export/pdf', [TimerCardController::class, 'exportPdf'])->name('export.pdf');
    // Sub nav Active Therapists (Admin only)
    Route::get('/active-therapists', [ActiveTherapistController::class, 'index'])->name('active-therapists.index');
    Route::get('/active-therapists/create', [ActiveTherapistController::class, 'create'])->name('active-therapists.create');
    Route::post('/active-therapists', [ActiveTherapistController::class, 'store'])->name('active-therapists.store');
    Route::get('/active-therapists/{therapist}/edit', [ActiveTherapistController::class, 'edit'])->name('active-therapists.edit');
    Route::put('/active-therapists/{therapist}', [ActiveTherapistController::class, 'update'])->name('active-therapists.update');
    Route::delete('/active-therapists/{therapist}', [ActiveTherapistController::class, 'destroy'])->name('active-therapists.destroy');
});

require __DIR__ . '/auth.php';