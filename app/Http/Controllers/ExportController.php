<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rekap; // Use the Rekap model
use Barryvdh\DomPDF\Facade as PDF; // Import the PDF facade if you're using domPDF

class ExportController extends Controller
{
    public function show()
    {
        // Fetch data for the export from the 'rekaps' table
        $query = Rekap::join('timer_cards', 'rekaps.timer_card_id', '=', 'timer_cards.id') // Adjust the table name as needed
        ->select('rekaps.*') // Select all columns from rekaps
        ->orderByRaw('CAST(SUBSTRING_INDEX(timer_cards.card_name, " ", -1) AS UNSIGNED) ASC');

        $rekaps = $query->get();
        // Return the view and pass the rekaps data to it
        return view('export', compact('rekaps'));
    }

    public function exportPdf()
    {
        // Fetch data for exporting to PDF
        $rekaps = Rekap::with('timerCard')->get();

        // Use the PDF library to create a PDF from a view
        $pdf = PDF::loadView('pdf.rekap_export', compact('rekaps')); // Ensure you have a view at 'resources/views/pdf/rekap_export.blade.php'

        // Return the generated PDF file as a download
        return $pdf->download('rekaps_export.pdf');
    }
}