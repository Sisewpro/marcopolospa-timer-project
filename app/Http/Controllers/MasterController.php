<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Therapist;

class MasterController extends Controller
{
    public function index(Request $request)
    {
        // Get start date and end date from request (if needed)
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Fetch therapists with optional date filtering
        $query = Therapist::query();

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $therapists = $query->get();

        return view('master', compact('therapists'));
    }
}
