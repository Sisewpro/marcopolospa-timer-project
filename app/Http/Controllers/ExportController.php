<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimerCard;

class ExportController extends Controller
{
    public function show()
    {
        // Fetch data for the export
        $timerCards = TimerCard::all();
        return view('export', compact('timerCards'));
    }

    public function exportPdf()
    {
        // Logic for exporting data to PDF
        // You can use libraries like domPDF for this
    }
}