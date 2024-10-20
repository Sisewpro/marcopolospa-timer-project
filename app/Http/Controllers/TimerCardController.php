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

        $therapists = Therapist::where('status', 'active')->with('timerCards')->get()->map(function ($therapist) {
            $therapist->isUnavailable = $therapist->availability_status === 'non-available';
            return $therapist;
        });

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
    $timerCard = TimerCard::findOrFail($id);
    $oldTherapistId = $timerCard->therapist_id;

    // Validate incoming request data
    $validatedData = $request->validate([
        'card_name' => 'required|string|max:255',
        'therapist_id' => 'nullable|exists:therapists,id',
        'customer' => 'nullable|string|max:255',
        'time' => 'required|string', // Assuming time is in 'HH:MM:SS' format
    ]);

    // Update the timer card
    $timerCard->update([
        'card_name' => $validatedData['card_name'],
        'therapist_id' => $validatedData['therapist_id'],
        'customer' => $validatedData['customer'],
        'time' => $validatedData['time'],
    ]);

    // If the therapist is changed to None, make the old therapist available again
    if ($oldTherapistId && !$validatedData['therapist_id']) {
        $therapist = Therapist::find($oldTherapistId);
        $therapist->availability_status = 'available'; // Update to available
        $therapist->save();
    }

    // If a new therapist is selected, mark them as unavailable
    if ($validatedData['therapist_id']) {
        $newTherapist = Therapist::find($validatedData['therapist_id']);
        $newTherapist->availability_status = 'non-available'; // Mark as unavailable
        $newTherapist->save();
    }

    return redirect()->route('timer-cards.index')->with('success', 'Timer card updated successfully.');
}

    // Menghapus locker card
    public function destroy($id)
    {
        $card = TimerCard::findOrFail($id);

        if ($card->therapist_id) {
            $therapist = Therapist::findOrFail($card->therapist_id);
            $therapist->availability_status = 'available';
            $therapist->save();
        }
        
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

        // Mengubah status ke "Available"
        if ($timerCard->therapist_id) {
            $therapist = Therapist::findOrFail($timerCard->therapist_id);
            $therapist->availability_status = 'available';
            $therapist->save();
        }

        // Simpan waktu akhir dan ubah status ke "Ready"
        $timerCard->update([
            'status' => 'Ready', // Status dikembalikan ke Ready
            'time' => '01:30:00', // Reset waktu ke default 01:30:00
            'end_time' => now(), // Simpan waktu selesai
            'therapist_id' => null, // Set therapist_id ke null untuk menunjukkan "None"
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

        $query = Rekap::query();

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $rekaps = $query->with('timerCard')->get();

        $pdf = Pdf::loadView('pdf.rekaps', compact('rekaps'));

        return $pdf->download('rekapdata_marcopolo.pdf');
    }
}