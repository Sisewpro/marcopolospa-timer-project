<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CountTimeController; // Import controller untuk counttime
use App\Http\Controllers\TimerCardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.register');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/counttime', [CountTimeController::class, 'index'])->name('counttime');
});

Route::get('/dashboard', [TimerCardController::class, 'index'])->name('dashboard');
Route::post('/timer-cards', [TimerCardController::class, 'store'])->name('timer-cards.store');
Route::delete('/timer-cards/{id}', [TimerCardController::class, 'destroy'])->name('timer-cards.destroy');
Route::post('/timer-cards/{id}/update', [TimerCardController::class, 'update'])->name('timer-cards.update');
Route::put('/timer-cards/{id}', [TimerCardController::class, 'update'])->name('timer-cards.update');

require __DIR__.'/auth.php';