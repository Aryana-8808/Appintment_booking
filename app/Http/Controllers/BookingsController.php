<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\AppointmentSlot;
use Illuminate\Support\Facades\Validator;
use Google\Client as GoogleClient;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;
use Illuminate\Support\Facades\DB;

class BookingsController extends Controller
{
    // Show the user dashboard
    public function index()
    {
        return view('User.index');
    }

    public function about(){
        return view('User.AboutClinic');
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
    // public function create()

    // {
    //     $bookings = Booking::where('status', '!=', 'cancelled')->get();
    //     $availableSlots = AppointmentSlot::where('available', true)->get();
    
    //     return view('User.create', compact('bookings', 'availableSlots'));
    // }

    public function create()
{
    // Check if user is authenticated
    if (!auth()->check()) {
        return redirect()->route('account.login'); // Redirect to login if not authenticated
    }

    // Retrieve authenticated user ID
    $userId = auth()->id();

    // Query bookings or other data as needed
    $bookings = Booking::where('status', '!=', 'cancelled')->get();
    $availableSlots = AppointmentSlot::where('available', true)->get();

    return view('User.create', compact('bookings', 'availableSlots', 'userId'));
}
    
    // Store a newly created appointment in the database
//     public function store(Request $request)
// {
//     try {
//         $rules = [
//             'name' => 'required|max:255',
//             'appointment_date' => 'required|date|after:now',
//             'duration' => 'required|integer|min:15',
//             'phone' => 'required|digits:10',
//             'status' => 'required|in:pending,confirmed,completed,cancelled',
//             'description' => 'nullable|string|max:255',
//             'slot_id' => 'required|exists:appointment_slots,id',
//         ];

//         $availableSlots = AppointmentSlot::where('available', true)
//             ->get(['start_time', 'end_time']);

//         // Validate the incoming request
//         $validator = Validator::make($request->all(), $rules);

        

//         // Throw validation exception if fails
//         if ($validator->fails()) {
//             throw new \Illuminate\Validation\ValidationException($validator);
//         }

//         $slot = AppointmentSlot::find($request->slot_id);
//         if (!$slot) {
//             throw new \Exception('Selected appointment slot not found.');
//         }

//         // Example: Adjust start and end times based on slot
//         $appointment_start = Carbon::parse($request->appointment_date)->setTimeFrom($slot->start_time);
//         $appointment_end = $appointment_start->copy()->addMinutes($request->duration);

        

//         // Create new booking instance
//         $booking = new Booking();
//         $booking->user_id = auth()->id();
//         $booking->name = $request->name;
//         $booking->appointment_date = $request->appointment_date;
//         $booking->duration = $request->duration;
//         $booking->phone = $request->phone;
//         $booking->status = $request->status;
//         $booking->description = $request->reason;
//         $booking->save();

        

//         return redirect()->route('bookings.show')->with('success', 'Appointment created successfully.');
//     } catch (\Exception $e) {
//         // Handle any exceptions that occur during the process
//         dd($e);
//     }
// }


// public function store(Request $request)
// {
//     try {
//         $rules = [
//             'name' => 'required|max:255',
//             'appointment_date' => 'required|date|after:now',
//             'phone' => 'required|digits:10',
//             'status' => 'required|in:pending,confirmed,completed,cancelled',
//             'description' => 'nullable|string|max:255',
//             'slot_id' => 'required|exists:appointment_slots,id',
//             'duration' => 'required|integer|min:15',
//         ];

//         $validator = Validator::make($request->all(), $rules);

//         if ($validator->fails()) {
//             return redirect()->back()->withErrors($validator)->withInput();
//         }

//         $selectedSlot = AppointmentSlot::findOrFail($request->slot_id);

//         $startTime = Carbon::parse($selectedSlot->start_time);
//         $endTime = Carbon::parse($selectedSlot->end_time);

//         $appointmentStart = Carbon::parse($request->appointment_date);
//         $requestedDuration = (int) $request->duration;
//         $appointmentEnd = $appointmentStart->copy()->addMinutes($requestedDuration);

//         // Check for overlapping bookings
//         $overlappingBooking = Booking::where('appointment_date', '<', $appointmentEnd)
//                                     ->where('end_time', '>', $appointmentStart)
//                                     ->exists();

//         // if ($overlappingBooking) {
//         //     return redirect()->route('bookings.create')->with('error', 'Booking cannot be made. There is already an overlapping booking.');
//         // }

//         if ($overlappingBooking) {
//             // Find the nearest available slot if overlapping
//             $nearestSlot = AppointmentSlot::where('id', '!=', $request->slot_id) // Exclude the current slot
//                                           ->where('start_time', '>=', $endTime) // Find slots starting after the requested slot ends
//                                           ->where('available', true)
//                                           ->orderBy('start_time')
//                                           ->first();

//             if (!$nearestSlot) {
//                 return redirect()->route('bookings.create')->with('error', 'Booking cannot be made. There is already an overlapping booking and no available slots afterwards.');
//             }

//             return redirect()->route('bookings.create')->with('error', 'Booking cannot be made. There is already an overlapping booking. Nearest available slot: ' . $nearestSlot->start_time . ' - ' . $nearestSlot->end_time);
//         }

//         // Retrieve all slots that are not booked within the requested time frame
//         $availableSlots = AppointmentSlot::whereNotBetween('start_time', [$appointmentStart, $appointmentEnd])
//                                         ->orWhereNotBetween('end_time', [$appointmentStart, $appointmentEnd])
//                                         ->get();

//         // Create new booking instance
//         $booking = new Booking();
//         $booking->user_id = $request->user_id;
//         $booking->name = $request->name;
//         $booking->appointment_date = $appointmentStart;
//         $booking->slot_id = $request->slot_id;
//         $booking->duration = $requestedDuration;
//         $booking->phone = $request->phone;
//         $booking->end_time = $appointmentEnd;
//         $booking->status = $request->status;
//         $booking->description = $request->description;
//         $booking->save();

//         return redirect()->route('account.dashboard')->with('success', 'Appointment created successfully.');
//     } catch (\Illuminate\Database\QueryException $e) {
//         if ($e->errorInfo[1] == 1062) { 
//             return redirect()->route('account.dashboard')->with('error', 'Duplicate entry: A booking already exists for this user at the selected date and time.');
//         } else {
//             return redirect()->route('account.dashboard')->with('error', 'Database error: ' . $e->getMessage());
//         }
//     } catch (\Exception $e) {
//         return redirect()->route('bookings.create')->with('error', 'Error creating appointment: ' . $e->getMessage());
//     }
// }


public function store(Request $request)
{
    try {
        $rules = [
            'name' => 'required|max:255',
            'appointment_date' => 'required|date|after:now',
            'phone' => 'required|digits:10',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'description' => 'nullable|string|max:255',
            'slot_id' => 'required|exists:appointment_slots,id',
            'duration' => 'required|integer|min:15',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $selectedSlot = AppointmentSlot::findOrFail($request->slot_id);

        $startTime = Carbon::parse($selectedSlot->start_time);
        $endTime = Carbon::parse($selectedSlot->end_time);

        $appointmentStart = Carbon::parse($request->appointment_date);
        $requestedDuration = (int) $request->duration;
        $appointmentEnd = $appointmentStart->copy()->addMinutes($requestedDuration);

        // Check for overlapping bookings
        $overlappingBooking = Booking::where('appointment_date', '<', $appointmentEnd)
                                    ->where('end_time', '>', $appointmentStart)
                                    ->exists();

        if ($overlappingBooking) {
            // Find the nearest available slot if overlapping
            $nearestSlot = AppointmentSlot::where('id', '!=', $request->slot_id) // Exclude the current slot
                                          ->where('start_time', '>=', $endTime) // Find slots starting after the requested slot ends
                                          ->where('available', true)
                                          ->orderBy('start_time')
                                          ->first();

            if (!$nearestSlot) {
                return redirect()->route('bookings.create')->with('error', 'Booking cannot be made. There is already an overlapping booking and no available slots afterwards.');
            }

            return redirect()->route('bookings.create')->with('error', 'Booking cannot be made. There is already an overlapping booking. Nearest available slot: ' . $nearestSlot->start_time . ' - ' . $nearestSlot->end_time);
        }


        
        // Retrieve all slots that are not booked within the requested time frame
        $availableSlots = AppointmentSlot::whereNotBetween('start_time', [$appointmentStart, $appointmentEnd])
                                        ->orWhereNotBetween('end_time', [$appointmentStart, $appointmentEnd])
                                        ->get();

        // Create new booking instance
        $booking = new Booking();
        $booking->user_id = $request->user_id;
        $booking->name = $request->name;
        $booking->appointment_date = $appointmentStart;
        $booking->slot_id = $request->slot_id;
        $booking->duration = $requestedDuration;
        $booking->phone = $request->phone;
        $booking->end_time = $appointmentEnd;
        $booking->status = $request->status;
        $booking->description = $request->description;
        $booking->save();

        return redirect()->route('account.dashboard')->with('success', 'Appointment created successfully.');
    } catch (\Illuminate\Database\QueryException $e) {
        if ($e->errorInfo[1] == 1062) { 
            return redirect()->route('account.dashboard')->with('error', 'Duplicate entry: A booking already exists for this user at the selected date and time.');
        } else {
            return redirect()->route('account.dashboard')->with('error', 'Database error: ' . $e->getMessage());
        }
    } catch (\Exception $e) {
        return redirect()->route('bookings.create')->with('error', 'Error creating appointment: ' . $e->getMessage());
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
       

        $booking = Booking::find($id);
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
            return redirect()->route('appointment.edit', $id)->withErrors($validator)->withInput();
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

    public function show()
{
    try {
        // Fetch all bookings of the authenticated user
        $bookings = Booking::where('user_id', auth()->id())
            ->where('status', '!=', 'cancelled') // Exclude cancelled bookings if needed
            ->get();

        // Prepare data for FullCalendar
        $events = [];
        foreach ($bookings as $booking) {
            $events[] = [
                'title' => 'Appointment with ' . $booking->name,
                'start' => $booking->appointment_date,
                'end' => Carbon::parse($booking->appointment_date)->addMinutes($booking->duration),
                
            ];
        }

        // Pass events data to the view
        return view('User.show', compact('events'));

    } catch (\Exception $e) {
        // Handle any exceptions
        dd($e);
    }
}

}
