<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\AppointmentSlot;
use Illuminate\Support\Facades\Validator;

class BookingsController extends Controller
{
    // Show the user dashboard
    public function index()
    {
        return view('User.index');
    }

    // Show the appointments dashboard
    public function appointment()
    {
        $userId = auth()->id();
        $appointments = Booking::where('user_id', $userId)->get();
        return view('dashboard', compact('appointments'));
    }

    // Check if the time slot is available
    public function isAvailable($appointment_date, $duration)
    {
        $end_date = Carbon::parse($appointment_date)->addMinutes($duration);
        
        return !Booking::where('appointment_date', '<', $end_date)
            ->where('end_time', '>', $appointment_date)
            ->exists();
    }

    // Show the form to create a new appointment
    public function create()
    {
        $bookings = Booking::where('status', '!=', 'cancelled')->get();
        $availableSlots = AppointmentSlot::where('available', true)->get();
    
        return view('User.create', compact('bookings', 'availableSlots'));
    }
    
    // Store a newly created appointment in the database
    public function store(Request $request)
{
    try {
        $rules = [
            'name' => 'required|max:255',
            'appointment_date' => 'required|date|after:now',
            'duration' => 'required|integer|min:15',
            'phone' => 'required|digits:10',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'description' => 'nullable|string|max:255',
            'slot_id' => 'required|exists:appointment_slots,id',
        ];

        // Validate the incoming request
        $validator = Validator::make($request->all(), $rules);

        // Throw validation exception if fails
        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        // Check for overlapping bookings
        // $appointmentStart = Carbon::parse($request->appointment_date);
        // $appointmentEnd = $appointmentStart->copy()->addMinutes((int) $request->duration);

        // $overlappingBookings = Booking::where('appointment_date', '<', $appointmentEnd)
        //     ->where('appointment_date', '>', $appointmentStart)
        //     ->exists();

        // if ($overlappingBookings) {
        //     throw new \Exception('This time slot is unavailable.');
        // }

        // // Mark the selected slot as unavailable
        // $slot = AppointmentSlot::find($request->slot_id);
        // if (!$slot) {
        //     throw new \Exception('Appointment slot not found.');
        // }
        // $slot->available = false;
        // $slot->save();

        // Create new booking instance
        $booking = new Booking();
        $booking->user_id = auth()->id();
        $booking->name = $request->name;
        $booking->appointment_date = $appointmentStart;
        // $booking->duration = $request->duration;
        $booking->phone = $request->phone;
        $booking->status = $request->status;
        $booking->description = $request->description;
        $booking->save();

        return redirect()->route('bookings.appointment')->with('success', 'Appointment created successfully.');
    } catch (\Exception $e) {
        // Handle any exceptions that occur during the process
        dd($e);
    }
}


    // Show the form to edit an appointment
    public function edit($id)
    {
        $booking = Booking::findOrFail($id);

        // Check if the user is authorized to edit this booking
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        return view('User.edit', compact('booking'));
    }

    // Update the specified appointment in the database
    public function update($id, Request $request)
    {
        $booking = Booking::findOrFail($id);

        // Check if the user is authorized to update this booking
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

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
            return redirect()->route('bookings.edit', $id)->withErrors($validator)->withInput();
        }

        // Update the booking details
        $appointmentStart = Carbon::parse($request->appointment_date);
        $booking->name = $request->name;
        $booking->appointment_date = $appointmentStart;
        $booking->duration = $request->duration;
        $booking->phone = $request->phone;
        $booking->status = $request->status;
        $booking->description = $request->description;
        $booking->save();

        return redirect()->route('bookings.appointment')->with('success', 'Appointment updated successfully.');
    }

    // Delete the specified appointment from the database
    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);

        // Check if the user is authorized to delete this booking
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        // Delete the booking
        $booking->delete();

        return redirect()->route('bookings.appointment')->with('success', 'Appointment deleted successfully.');
    }
}
