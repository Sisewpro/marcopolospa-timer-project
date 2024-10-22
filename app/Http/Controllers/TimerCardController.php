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
    // public function index()
    // {
    //     $timerCards = TimerCard::with('user', 'therapist')->get(); // Ambil semua card dengan user
    //     $therapists = Therapist::where('status', 'active')->get(); // Ambil therapist aktif

    //     return view('dashboard', compact('timerCards', 'therapists'));
    // }

    public function index()
    {
        $timerCards = TimerCard::with('user', 'therapist')->get();

        foreach ($timerCards as $card) {
            if ($card->status === 'Running') {
                $remainingTime = $this->calculateRemainingTime($card->start_time, $card->time);
                $card->time = $remainingTime;
            }
        }

        $therapists = Therapist::where('status', 'active')->get();

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
            'customer' => 'nullable|string|max:255',
            'time' => 'required|string', // Validasi untuk input waktu
        ]);

        $card = TimerCard::findOrFail($id);
        $card->update([
            'card_name' => $request->input('card_name'),
            'therapist_id' => $request->input('therapist_id'),
            'customer' => $request->input('customer'),
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

        // Set waktu mulai sekarang dan status "Running"
        $timerCard->update([
            'status' => 'Running',
            'customer' => $request->customer,
            'start_time' => now(), // Simpan waktu mulai
        ]);

        // Simpan data ke tabel rekap
        Rekap::create([
            'timer_card_id' => $timerCard->id,
            'customer' => $timerCard->customer,
            'therapist_name' => $timerCard->therapist->name ?? 'No Therapist',
            'time' => $timerCard->time,
            'status' => 'Running'
        ]);

        return redirect()->route('dashboard')->with('success', 'Timer dimulai.');
    }

    private function calculateRemainingTime($startTime, $initialTime)
    {
        // Konversi waktu mulai ke timestamp
        $startTimestamp = strtotime($startTime);

        // Hitung selisih waktu dalam detik antara sekarang dan waktu mulai
        $currentTimestamp = now()->timestamp;
        $elapsedSeconds = $currentTimestamp - $startTimestamp;

        // Konversi waktu awal (format 00:00:00) ke detik
        $initialSeconds = $this->convertTimeToSeconds($initialTime);

        // Hitung waktu tersisa
        $remainingSeconds = max(0, $initialSeconds - $elapsedSeconds); // Tidak boleh kurang dari 0

        // Kembalikan dalam format 00:00:00
        return $this->convertSecondsToTime($remainingSeconds);
    }

    public function stop($id)
    {
        // Ambil TimerCard berdasarkan ID
        $timerCard = TimerCard::findOrFail($id);

        // Simpan waktu akhir dan ubah status ke "Ready"
        $timerCard->update([
            'status' => 'Ready', // Status dikembalikan ke Ready
            'time' => '01:30:00', // Reset waktu ke default 01:30:00
            'end_time' => now(), // Simpan waktu selesai
        ]);

        return redirect()->route('dashboard')->with('success', 'Timer dihentikan dan waktu dikembalikan ke default.');
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

        // Update waktu juga di tabel Rekap
        $rekap = Rekap::where('timer_card_id', $timerCard->id)
        ->where('status', 'Running')
        ->latest() // Mengambil rekap yang paling terbaru
        ->first();

        if ($rekap) {
        // Update waktu di tabel Rekap
        $rekap->time = $timerCard->time;
        $rekap->save();
        }

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