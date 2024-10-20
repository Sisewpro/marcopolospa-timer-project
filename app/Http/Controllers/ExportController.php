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
        $rekaps = Rekap::with('timerCard')->get(); // Include related timerCard data

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