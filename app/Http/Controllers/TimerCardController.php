<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimerCard;
use App\Models\User;

class TimerCardController extends Controller
{
    public function index()
    {
        $timerCards = TimerCard::with('user')->get();
        $users = User::all();
        return view('dashboard', compact('timerCards', 'users'));
    }

    public function store(Request $request)
    {
        // Hitung jumlah card yang sudah ada
        $count = TimerCard::count();

        // Tentukan nama default card (Locker 1, Locker 2, ...)
        $cardName = 'Locker ' . ($count + 1);
        
        // Buat card baru dengan nilai default atau waktu dari input jika ada
        TimerCard::create([
            'card_name' => $cardName,
            'user_id' => null, // null berarti belum ada user yang ditetapkan
            'time' => $request->input('time', '01:30:00'), // Waktu default 1 jam 30 menit jika tidak ada input
            'status' => 'Ready',
        ]);

        return redirect()->route('dashboard')->with('success', 'Locker berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        // Cari timer card berdasarkan ID
        $timerCard = TimerCard::findOrFail($id);
        
        // Hapus timer card tersebut
        $timerCard->delete();
        
        // Redirect ke halaman dashboard setelah penghapusan
        return redirect()->route('dashboard')->with('success', 'Locker berhasil dihapus.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'card_name' => 'required|string|max:255',
            'time' => 'required|date_format:H:i:s',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $timerCard = TimerCard::findOrFail($id);
        $timerCard->card_name = $request->input('card_name');
        $timerCard->time = $request->input('time');
        $timerCard->user_id = $request->input('user_id') ?: null;
        $timerCard->save();

        return redirect()->route('dashboard')->with('success', 'Locker berhasil diperbarui!');
    }
}
