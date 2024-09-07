<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimerCard;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TimerCardController extends Controller
{
    public function index()
    {
        // Mengambil semua timer cards beserta user terkait
        $timerCards = TimerCard::with('user')->get();
        $users = User::all();

        // Mengembalikan view dashboard dengan data timerCards dan users
        return view('dashboard', compact('timerCards', 'users'));
    }

    public function start(Request $request, $id)
    {
        // Mencari timer card berdasarkan ID
        $timerCard = TimerCard::findOrFail($id);

        // Mengubah status timer menjadi 'running' dan menyimpan input waktu dan customer
        $timerCard->status = 'running';
        $timerCard->time = $request->input('time');
        $timerCard->customer = $request->input('customer');
        $timerCard->save();

        // Mengembalikan response JSON berhasil
        return response()->json(['success' => true]);
    }

    public function addSession(Request $request, $id)
    {
        // Mencari timer card berdasarkan ID
        $timerCard = TimerCard::findOrFail($id);

        // Menambahkan waktu berdasarkan input sessionMinutes
        $additionalTime = $request->input('sessionMinutes') * 60;
        $timerCard->time += $additionalTime;
        $timerCard->save();

        // Mengembalikan response JSON berhasil
        return response()->json(['success' => true]);
    }

    public function store(Request $request)
    {
        // Membuat instance baru untuk TimerCard
        $timerCard = new TimerCard();
        $timerCard->card_name = $request->input('cardName');  // Mengambil nama card
        $timerCard->user_id = Auth::user()->id;               // Mengambil ID user yang sedang login
        $timerCard->time = $request->input('time');           // Mengambil input waktu
        $timerCard->customer = $request->input('customer');   // Mengambil input customer
        $timerCard->status = 'ready';                         // Mengatur status awal menjadi 'ready'
        $timerCard->save();                                   // Menyimpan data ke database

        // Mengembalikan response JSON berhasil
        return response()->json(['success' => true]);
    }
}
