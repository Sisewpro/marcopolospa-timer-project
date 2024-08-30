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

        // Tentukan nama default user (staff 1, 2, 3, ...)
        $defaultUserName = 'staff ' . ($count + 1);

        // Buat card baru dengan nilai default
        TimerCard::create([
            'card_name' => 'Loket ' . ($count + 1),
            'user_id' => null,
            'user_name' => $defaultUserName,
            'time' => '00:00:00',
            'status' => 'Ready',
        ]);

        return redirect()->route('dashboard');
    }
    public function destroy($id)
    {
        // Cari timer card berdasarkan ID
        $timerCard = TimerCard::findOrFail($id);
        
        // Hapus timer card tersebut
        $timerCard->delete();
        
        // Redirect ke halaman dashboard setelah penghapusan
        return redirect()->route('dashboard')->with('success', 'Timer card berhasil dihapus.');
    }

    public function update(Request $request, $id)
    {
        $timerCard = TimerCard::findOrFail($id);

        $timerCard->card_name = $request->input('card_name');
        $timerCard->time = $request->input('time');
        $timerCard->user_id = $request->input('user_id'); // Update user_id
        $timerCard->save();

        return redirect()->route('dashboard')->with('success', 'Card berhasil diperbarui!');
    }


}
