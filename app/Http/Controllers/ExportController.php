<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rekap; // Use the Rekap model
use Barryvdh\DomPDF\PDF; // Import the PDF class directly (updated version)

class ExportController extends Controller
{
    public function show(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Fetch data for the export from the 'rekaps' table
        $query = Rekap::join('timer_cards', 'rekaps.timer_card_id', '=', 'timer_cards.id')
            ->select('rekaps.*', 'timer_cards.card_name') // Seleksi kolom yang dibutuhkan
            ->orderByRaw('CAST(SUBSTRING_INDEX(timer_cards.card_name, " ", -1) AS UNSIGNED) ASC');

        // Filter based on start_date and end_date if they are provided
        if ($startDate) {
            $query->whereDate('rekaps.created_at', '>=', $startDate); // Pastikan menggunakan 'rekaps.created_at'
        }

        if ($endDate) {
            $query->whereDate('rekaps.created_at', '<=', $endDate); // Pastikan menggunakan 'rekaps.created_at'
        }

        $rekaps = $query->get();

        // Return the view and pass the rekaps data to it
        return view('export', compact('rekaps'));
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
    
        // Check if the start date is set and format it with 00:00:00 (midnight)
        if ($startDate) {
            $startDate = \Carbon\Carbon::parse($startDate)->startOfDay(); // Set time to 00:00:00
        }
    
        // Check if the end date is set and format it with 23:59:59 (end of day)
        if ($endDate) {
            $endDate = \Carbon\Carbon::parse($endDate)->endOfDay(); // Set time to 23:59:59
        }
    
        // Fetch the data with filtering based on the formatted dates
        $rekaps = Rekap::with('timerCard')
            ->when($startDate, function ($query, $startDate) {
                $query->whereDate('rekaps.created_at', '>=', $startDate); // Filter starting from the start date
            })
            ->when($endDate, function ($query, $endDate) {
                $query->whereDate('rekaps.created_at', '<=', $endDate); // Filter up to the end date
            })
            ->get();
    
        // Format the created_at column if needed
        $rekaps = $rekaps->map(function ($rekap) {
            $rekap->formatted_date = \Carbon\Carbon::parse($rekap->created_at)
                ->setTimezone('Asia/Jakarta')
                ->translatedFormat('j F Y');
            $rekap->formatted_time = \Carbon\Carbon::parse($rekap->created_at)
                ->setTimezone('Asia/Jakarta')
                ->translatedFormat('H:i:s') . ' WIB';
            return $rekap;
        });
    
        // Use PDF to generate the file
        $pdf = app(PDF::class)->loadView('pdf.rekaps', compact('rekaps'));
    
        // Return the generated PDF as a download
        return $pdf->download('rekaps.pdf');
    }    
}