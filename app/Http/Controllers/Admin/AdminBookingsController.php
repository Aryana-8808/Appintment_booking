<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AdminBookingsController extends Controller
{
    public function index()
    {
        $appointments = Booking::all();
        return view('admin.AdminDashboard', compact('appointments'));
    }

        public function create()
    {
        return view('admin.AdminCreate');
    }

    
    // Store a newly created appointment in the database
    public function store(Request $request)
    {
        // Validation rules
        $rules = [
            'name' => 'required|max:255',
            'appointment_date' => 'required|date|after:now',
            'duration' => 'required|integer|min:15',
            'phone' => 'required|digits:10',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'description' => 'nullable|string|max:255',
        ];
    
        // Validate the request
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        // Retrieve admin ID
        $adminId = auth('admin')->id();
    
        // Create new booking
        $booking = new Booking();
        $booking->user_id = $adminId; // Assign admin's ID to user_id
        $booking->name = $request->input('name');
        $booking->appointment_date = $request->input('appointment_date');
        $booking->duration = $request->input('duration');
        $booking->phone = $request->input('phone');
        $booking->status = $request->input('status');
        $booking->description = $request->input('description');
        $booking->save();
    
        // Redirect with success message
        return redirect()->route('admin.AdminDashboard')->with('success', 'Appointment created successfully.');
    }
    
    
    // Show the form to edit an appointment
    public function edit($id)
    {
        $booking = Booking::findOrFail($id);
        return view('admin.AdminEdit', compact('booking'));
    }



    // Update the specified appointment in the database
    public function update($id, Request $request)
    {
        $booking = Booking::find($id);

        $rules = [
            'name' => 'required|max:255',
            'appointment_date' => 'required|date|unique:bookings,appointment_date,' . $booking->id,
            'phone' => 'required|digits:10',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'duration' => 'required|integer|min:15',
            'description' => 'nullable|string|max:255',
        ];
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->route('admin.AdminEdit', $id)->withErrors($validator)->withInput();
        }

        $appointmentStart = Carbon::parse($request->appointment_date);
        $booking->name = $request->name;
        $booking->appointment_date = $appointmentStart;
        $booking->duration = $request->duration;
        $booking->phone = $request->phone;
        $booking->status = $request->status;
        $booking->description = $request->description;
        $booking->save();

        return redirect()->route('admin.AdminDashboard')->with('success', 'Appointment updated successfully.');
    }

    // Delete the specified appointment from the database
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return redirect()->route('admin.AdminDashboard')->with('success', 'Appointment deleted successfully.');
    }
}
