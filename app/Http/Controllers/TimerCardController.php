<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimerCard;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TimerCardController extends Controller
{
    // Menampilkan semua locker card
    public function index()
    {
        $timerCards = TimerCard::with('user')->get(); // Ambil semua card dengan user
        $users = User::where('role', '!=', 'admin')->get(); // Ambil user non-admin untuk pilihan staff
        return view('dashboard', compact('timerCards', 'users'));
    }

    // Menyimpan locker baru
    public function store(Request $request)
    {
        $count = TimerCard::count();

        TimerCard::create([
            'card_name' => 'Locker ' . ($count + 1),
            'user_id' => $request->input('user_id'),
            'customer' => null,
            'time' => '01:30:00', // waktu default 90 menit
            'status' => 'Ready',
        ]);

        return redirect()->route('dashboard')->with('success', 'Locker baru berhasil ditambahkan.');
    }

    // Mengedit locker card
    public function update(Request $request, $id)
    {
        $request->validate([
            'card_name' => 'required|string|max:255',
            'user_id' => 'nullable|exists:users,id',
            'time' => 'required|string', // Validasi untuk input waktu
        ]);

        $card = TimerCard::findOrFail($id);
        $card->update([
            'card_name' => $request->input('card_name'),
            'user_id' => $request->input('user_id'),
            'time' => $request->input('time'), // Simpan waktu yang baru
        ]);

        return redirect()->route('dashboard')->with('success', 'Locker berhasil diubah.');
    }


    // Menghapus locker card
    public function destroy($id)
    {
        $card = TimerCard::findOrFail($id);
        $card->delete();

        return redirect()->route('dashboard')->with('success', 'Locker berhasil dihapus.');
    }

    // Memulai timer (ubah status menjadi 'Running', simpan data customer)
    public function start(Request $request, $id)
    {
        $card = TimerCard::findOrFail($id);

        // Pastikan input customer tidak kosong
        if (empty($request->customer)) {
            return response()->json(['success' => false, 'message' => 'Customer tidak boleh kosong.'], 400);
        }

        // Update data card
        $card->update([
            'customer' => $request->customer,
            'time' => $this->convertTimeToSeconds($request->time),
            'status' => 'Running',
        ]);

        return response()->json(['success' => true, 'message' => 'Timer dimulai.']);
    }

    // Menambah sesi ke timer card
    public function addSession(Request $request, $id)
    {
        $card = TimerCard::findOrFail($id);

        $additionalMinutes = $request->input('sessionMinutes');
        $additionalSeconds = $additionalMinutes * 60;
        $currentSeconds = $this->convertTimeToSeconds($card->time);

        // Tambahkan sesi ke waktu sekarang
        $newTotalSeconds = $currentSeconds + $additionalSeconds;
        $card->update(['time' => $this->convertSecondsToTime($newTotalSeconds)]);

        return response()->json(['success' => true, 'message' => 'Sesi ditambahkan.']);
    }

    // Fungsi helper untuk konversi waktu ke detik
    private function convertTimeToSeconds($time)
    {
        list($hours, $minutes, $seconds) = sscanf($time, '%d:%d:%d');
        return ($hours * 3600) + ($minutes * 60) + $seconds;
    }

    // Fungsi helper untuk konversi detik ke format waktu 00:00:00
    private function convertSecondsToTime($totalSeconds)
    {
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds); // Menggunakan sprintf, bukan gmdate
    }

    public function exportPdf(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

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