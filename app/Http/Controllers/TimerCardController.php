<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimerCard;
use Barryvdh\DomPDF\Facade\Pdf;
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
        $request->validate([
            'customer' => 'nullable|string|max:255',
        ]);

        $count = TimerCard::count();

        TimerCard::create([
            'card_name' => 'Locker ' . ($count + 1),
            'customer' => null,
            'user_id' => null,
            'time' => '01:30:00',
            'status' => 'Ready',
        ]);

        return redirect()->route('dashboard')->with('success', 'Locker berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $timerCard = TimerCard::findOrFail($id);
        $timerCard->delete();

        return redirect()->route('dashboard')->with('success', 'Locker berhasil dihapus.');
    }

    public function update(Request $request, $id)
    {
        $timerCard = TimerCard::findOrFail($id);

        $timerCard->card_name = $request->input('card_name');
        $timerCard->customer = $request->input('customer');
        $timerCard->time = $request->input('time');
        $timerCard->user_id = $request->input('user_id');
        $timerCard->save();

        return redirect()->route('dashboard')->with('success', 'Card berhasil diperbarui!');
    }

    public function updateCustomer(Request $request, $id)
    {
        $timerCard = TimerCard::findOrFail($id);

        $timerCard->customer = $request->input('customer');
        $timerCard->save();

        return response()->json(['success' => true]);
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = TimerCard::query();
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }
        $timerCards = $query->get();

        $pdf = Pdf::loadView('pdf.timer_cardspdf', compact('timerCards'));

        return $pdf->download('rekapdata_marcopolo.pdf');
    }
}