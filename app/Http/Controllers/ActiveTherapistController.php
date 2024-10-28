<?php

namespace App\Http\Controllers;

use App\Models\Therapist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActiveTherapistController extends Controller
{
    public function index()
    {
        $therapists = Therapist::orderBy('name', 'asc')->get();
        $userHasPermission = Auth::user()->role === 'admin'; // Check if the user is an admin

        return view('active_therapists.index', compact('therapists', 'userHasPermission'));
    }

    public function create()
    {
        // Check if the user is an admin
        if (Auth::user()->role !== 'admin') {
            return redirect('/dashboard')->with('error', 'Unauthorized access');
        }

        return view('active_therapists.create');
    }

    public function store(Request $request)
    {
        // Check if the user is an admin
        if (Auth::user()->role !== 'admin') {
            return redirect('/dashboard')->with('error', 'Unauthorized access');
        }

        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => [
                'required',
                'string',
                'unique:therapists,phone_number',
                'regex:/^(\+62|62)8[1-9][0-9]{6,11}$/'
            ],
            'status' => 'required|string|in:inactive', // Default status for admin creating a new therapist
        ]);

        // Create a new therapist
        Therapist::create([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'status' => $request->status,
        ]);

        return redirect()->route('active-therapists.index')->with('success', 'Therapist created successfully.');
    }

    public function edit(Therapist $therapist)
    {
        $userRole = Auth::user()->role;

        if ($userRole === 'admin') {
            return view('active_therapists.edit-admin', compact('therapist'));
        } elseif ($userRole === 'user') {
            return view('active_therapists.edit-user', compact('therapist'));
        }

        return redirect('/dashboard')->with('error', 'Unauthorized access');
    }


    public function update(Request $request, Therapist $therapist)
    {
        $userRole = Auth::user()->role;

        if ($userRole === 'admin') {
            // Admin can update only name and phone number
            $request->validate([
                'name' => 'required|string|max:255',
                'phone_number' => [
                    'required',
                    'string',
                    'regex:/^(\+62|62)8[1-9][0-9]{6,11}$/',
                    'unique:therapists,phone_number,' . $therapist->id,
                ],
            ]);

            // Update the therapist
            $therapist->update($request->only('name', 'phone_number'));
        } elseif ($userRole === 'user') {
            // User can only update the status
            $request->validate([
                'status' => 'required|string|in:active,inactive',
            ]);

            // Update only the status
            $therapist->update($request->only('status'));
        } else {
            return redirect('/dashboard')->with('error', 'Unauthorized access');
        }

        return redirect()->route('active-therapists.index')->with('success', 'Therapist updated successfully.');
    }

    public function destroy(Therapist $therapist)
    {
        // Check if the user is an admin
        if (Auth::user()->role !== 'admin') {
            return redirect('/dashboard')->with('error', 'Unauthorized access');
        }

        // Delete the therapist
        $therapist->delete();
        return redirect()->route('active-therapists.index')->with('success', 'Therapist deleted successfully.');
    }
}