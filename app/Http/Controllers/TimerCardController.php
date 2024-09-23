<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimerCard;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Therapist;
use App\Models\Rekap;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TimerCardController extends Controller
{
    // Menampilkan semua locker card
    public function index()
    {
        $timerCards = TimerCard::with('user', 'therapist')->get(); // Ambil semua card dengan user
        $therapists = Therapist::where('status', 'active')->get(); // Ambil therapist aktif
        return view('dashboard', compact('timerCards', 'therapists'));
    }

    // Menyimpan locker baru
    public function store(Request $request)
    {
        $count = TimerCard::count();

        TimerCard::create([
            'card_name' => 'Locker ' . ($count + 1),
            'therapist_id' => $request->input('therapist_id'),
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
            'therapist_id' => 'nullable|exists:therapists,id',
            'time' => 'required|string', // Validasi untuk input waktu
        ]);

        $card = TimerCard::findOrFail($id);
        $card->update([
            'card_name' => $request->input('card_name'),
            'therapist_id' => $request->input('therapist_id'),
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
        // Ambil TimerCard berdasarkan ID
        $timerCard = TimerCard::findOrFail($id);

        // Validasi input customer
        $request->validate([
            'customer' => 'required|string|max:255',
        ]);

        // Update status card ke 'Running'
        $timerCard->update([
            'status' => 'Running',
            'customer' => $request->customer,
        ]);

        // Simpan data ke tabel rekap
        Rekap::create([
            'timer_card_id' => $timerCard->id,
            'customer' => $request->customer,
            'therapist_name' => $timerCard->therapist->name ?? 'No Therapist',
            'time' => $timerCard->time,
            'status' => 'Running'
        ]);

        return redirect()->route('dashboard')->with('success', 'Data Tersimpan.');
    }

    // Menambah sesi ke timer card
    public function addSession(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'additionalMinutes' => 'required|integer',
        ]);

        // Cari TimerCard berdasarkan ID
        $timerCard = TimerCard::findOrFail($id);

        // Konversi waktu dari database ke detik
        $currentSeconds = $this->convertTimeToSeconds($timerCard->time);

        // Tambahkan waktu tambahan dalam detik
        $additionalSeconds = $request->additionalMinutes * 60;
        $newTotalSeconds = $currentSeconds + $additionalSeconds;

        // Simpan waktu baru dalam format jam:menit:detik
        $timerCard->time = $this->convertSecondsToTime($newTotalSeconds);
        $timerCard->save();

        return response()->json(['success' => true, 'newTime' => $timerCard->time]);
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
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
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