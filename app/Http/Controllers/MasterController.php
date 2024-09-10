<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimerCard;

class MasterController extends Controller
{
    public function index(Request $request)
    {
        // Get start date and end date from request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Fetch timer cards with filtering based on date range
        $query = TimerCard::query();

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $timerCards = $query->get()->map(function ($timerCard) {
            $timerCard->formatted_date = $timerCard->created_at->format('j F Y');
            return $timerCard;
        });

        return view('master', compact('timerCards'));
    }
}