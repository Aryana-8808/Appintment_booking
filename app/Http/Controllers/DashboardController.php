<?php

// DashboardController.php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // this method will show register page to user
    // public function index()
    // {
    //     $appointments = Booking::all();
    //     // $user_id = Auth::id(); // Get the ID of the authenticated user
    // // $appointments = Appointment::where('user_id', $user_id)->get(); // Fetch only the appointments for the authenticated user
    //     return view('dashboard')->with(compact('appointments'));
    // }

    public function index()
    {
        // Get the authenticated user's ko id
        $userId = Auth::id();

        // Fetch appointments for the authenticated user
        $appointments = Booking::where('user_id', $userId)->get();

        // Pass the appointments to the view
        return view('dashboard')->with(compact('appointments'));
    }

}

