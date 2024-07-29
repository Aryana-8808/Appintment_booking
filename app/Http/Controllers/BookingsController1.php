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
            // Find the nearest available slot on the same day if overlapping
            $nearestSlot = AppointmentSlot::where('id', '!=', $request->slot_id) // Exclude the current slot
                                          ->whereDate('start_time', $appointmentStart->toDateString()) // Same day
                                          ->where('available', true)
                                          ->orderBy('start_time')
                                          ->get()
                                          ->filter(function ($slot) use ($appointmentEnd) {
                                              return Carbon::parse($slot->start_time)->greaterThanOrEqualTo($appointmentEnd);
                                          })
                                          ->first();

            if (!$nearestSlot) {
                return redirect()->route('bookings.create')->with('error', 'Booking cannot be made. There is already an overlapping booking and no available slots on the same day.');
            }

            return redirect()->route('bookings.create')->with('error', 'Booking cannot be made. There is already an overlapping booking. Nearest available slot: ' . $nearestSlot->start_time . ' - ' . $nearestSlot->end_time);
        }

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






public function findNearestAvailableSlot($appointmentDate, $requestedDuration)
{
    // Define the start and end times for the given day (assuming 10 AM to 3 PM as fixed slots)
    $startTime = Carbon::parse($appointmentDate)->setTime(10, 0, 0);
    $endTime = Carbon::parse($appointmentDate)->setTime(15, 0, 0);

    // Retrieve all slots for the given day
    $slots = AppointmentSlot::where('available', true)
        ->where('start_time', '>=', $startTime)
        ->where('end_time', '<=', $endTime)
        ->orderBy('start_time')
        ->get();

    // Linear search through slots to find the nearest available slot
    foreach ($slots as $slot) {
        // Convert start_time and end_time to Carbon instances
        $slotStartTime = Carbon::parse($slot->start_time);
        $slotEndTime = Carbon::parse($slot->end_time);

        // Check for overlapping with existing bookings
        if (!$this->checkAvailability($appointmentDate, $slotStartTime, $slotEndTime, $requestedDuration)) {
            // If overlapping, continue to the next slot
            continue;
        }

        // Check if the slot is within the allowed timeframe after the overlapping slot
        if ($slotStartTime->greaterThanOrEqualTo($startTime) && $slotEndTime->lessThanOrEqualTo($endTime)) {
            // Found a suitable slot, return it
            return $slot;
        }
    }

    // No suitable slot found
    return null;
}

// Method to check if a slot is available
private function checkAvailability($appointmentDate, $slotStartTime, $slotEndTime, $requestedDuration)
{
    $appointmentStart = Carbon::parse($appointmentDate)->setTimeFrom($slotStartTime);
    $appointmentEnd = $appointmentStart->copy()->addMinutes($requestedDuration);

    return !Booking::where('appointment_date', '<', $appointmentEnd)
        ->where('end_time', '>', $appointmentStart)
        ->exists();
}


    


    // Show the form to edit an appointment
    public function edit($id)
    {
        $booking = Booking::findOrFail($id);
        
        // Check if the user is authorized to edit this booking
        // if ($booking->user_id !== auth()->id()) {
        //     abort(403);
        // }

        $availableSlots = AppointmentSlot::all();
        // dd($availableSlots);
       
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
            'appointment_date' => 'required|date|after:now|unique:bookings,appointment_date,' . $booking->id,
            'phone' => 'required|digits:10',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'duration' => 'required|integer|min:15',
            'description' => 'nullable|string|max:255',
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return redirect()->route('appointment.edit', $id)->withErrors($validator)->withInput();
        }
    
        $appointmentStart = Carbon::parse($request->appointment_date);
        $requestedDuration = (int) $request->duration;
        $appointmentEnd = $appointmentStart->copy()->addMinutes($requestedDuration);
    
        // Check for overlapping bookings
        $overlappingBooking = Booking::where('appointment_date', '<', $appointmentEnd)
                                    ->where('end_time', '>', $appointmentStart)
                                    ->where('id', '!=', $id) // Exclude current booking
                                    ->exists();
    
        if ($overlappingBooking) {
            // Linear search for the nearest available slot
            $nearestSlot = $this->findNearestAvailableSlot($appointmentStart, $requestedDuration);
    
            if (!$nearestSlot) {
                return redirect()->route('appointment.edit', $id)->with('error', 'Booking cannot be made. There is already an overlapping booking and no available slots afterwards.');
            }
    
            return redirect()->route('appointment.edit', $id)->with('error', 'Booking cannot be made. There is already an overlapping booking. Nearest available slot: ' . $nearestSlot->start_time . ' - ' . $nearestSlot->end_time);
        }


        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }
    
        $rules = [
            'name' => 'required|max:255',
            'appointment_date' => 'required|date|after:now|unique:bookings,appointment_date,' . $booking->id,
            'phone' => 'required|digits:10',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'duration' => 'required|integer|min:15',
            'description' => 'nullable|string|max:255',
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return redirect()->route('appointment.edit', $id)->withErrors($validator)->withInput();
        }
    
        $appointmentStart = Carbon::parse($request->appointment_date);
        $requestedDuration = (int) $request->duration;
        $appointmentEnd = $appointmentStart->copy()->addMinutes($requestedDuration);
    
        // Check for overlapping bookings
        $overlappingBooking = Booking::where('appointment_date', '<', $appointmentEnd)
                                    ->where('end_time', '>', $appointmentStart)
                                    ->where('id', '!=', $id) // Exclude current booking
                                    ->exists();
    
        if ($overlappingBooking) {
            // Linear search for the nearest available slot
            $nearestSlot = $this->findNearestAvailableSlot($appointmentStart, $requestedDuration);
    
            if (!$nearestSlot) {
                return redirect()->route('appointment.edit', $id)->with('error', 'Booking cannot be made. There is already an overlapping booking and no available slots afterwards.');
            }
    
            return redirect()->route('appointment.edit', $id)->with('error', 'Booking cannot be made. There is already an overlapping booking. Nearest available slot: ' . $nearestSlot->start_time . ' - ' . $nearestSlot->end_time);
        }


    
        // Update the booking details
        $booking->name = $request->name;
        $booking->appointment_date = $appointmentStart;
        $booking->duration = $requestedDuration;
        $booking->phone = $request->phone;
        $booking->status = $request->status;
        $booking->description = $request->description;
        $booking->end_time = $appointmentEnd;
        $booking->save();
    
        return redirect()->route('bookings.appointment')->with('success', 'Appointment updated successfully.');
    }
    


    // Delete the specified appointment from the database
    // public function destroy($id)
    // {
    //     $booking = Booking::findOrFail($id);

    //     // Check if the user is authorized to delete this booking
    //     if ($booking->user_id !== auth()->id()) {
    //         abort(403);
    //     }

    //     // Delete the booking
    //     $booking->delete();

    //     return redirect()->route('bookings.appointment')->with('success', 'Appointment deleted successfully.');
    // }



    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);

        // Check user authorization (unchanged)

        // Fetch users on the waitlist (if any)
        $waitlist = json_decode($booking->waitlist, true);

        // Delete the booking
        $booking->delete();

        // Notify and offer the slot to the first user on the waitlist (if available)
        if ($waitlist) {
            $firstUserId = array_shift($waitlist);
            $this->offerSlotToWaitlistUser($booking->appointment_date, $booking->duration, $firstUserId);
        }

        return redirect()->route('bookings.appointment')->with('success', 'Appointment deleted successfully.');
    }


    public function addToWaitlist(Request $request, $id)
{
    $booking = Booking::findOrFail($id);
    $user = Auth::user();

    $booking->addToWaitlist($user->id);

    return redirect()->back()->with('message', 'You have been added to the waitlist.');
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
        $booking = Booking::where('user_id', $userId)->first();
       
        if ($booking) {
            $appointment_date = $booking->appointment_date;
            $duration = $booking->duration;
            
            $end_date = Carbon::parse($appointment_date)->addMinutes($duration);
            return view('dashboard', compact('booking', 'end_date'));
        } else {
            $end_date = null;
            return view('dashboard', compact('end_date'));
        }
    }

    // Check if the time slot is available
    public function isAvailable($appointment_date, $duration)
    {
        $end_date = Carbon::parse($appointment_date)->addMinutes($duration);
        
        return !Booking::where('appointment_date', '<', $end_date)
            ->where('end_time', '>', $appointment_date)
            ->exists();
    }

    public function create()
    {
        if (!auth()->check()) {
            return redirect()->route('account.login');
        }

        $userId = auth()->id();
        $bookings = Booking::where('status', '!=', 'cancelled')->get();
        $availableSlots = AppointmentSlot::where('available', true)->get();

        return view('User.create', compact('bookings', 'availableSlots', 'userId'));
    }

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

            $overlappingBooking = Booking::where('appointment_date', '<', $appointmentEnd)
                                        ->where('end_time', '>', $appointmentStart)
                                        ->exists();

            if ($overlappingBooking) {
                $nearestSlot = AppointmentSlot::where('id', '!=', $request->slot_id)
                                              ->whereDate('start_time', $appointmentStart->toDateString())
                                              ->where('available', true)
                                              ->orderBy('start_time')
                                              ->get()
                                              ->filter(function ($slot) use ($appointmentEnd) {
                                                  return Carbon::parse($slot->start_time)->greaterThanOrEqualTo($appointmentEnd);
                                              })
                                              ->first();

                if (!$nearestSlot) {
                    return redirect()->route('bookings.create')->with('error', 'Booking cannot be made. There is already an overlapping booking and no available slots on the same day.');
                }

                return redirect()->route('bookings.create')->with('error', 'Booking cannot be made. There is already an overlapping booking. Nearest available slot: ' . $nearestSlot->start_time . ' - ' . $nearestSlot->end_time);
            }

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

    public function addToWaitlist(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        $user = auth()->user();

        $booking->addToWaitlist($user->id);

        return redirect()->back()->with('message', 'You have been added to the waitlist.');
    }

    public function show()
    {
        try {
            $bookings = Booking::where('user_id', auth()->id())
                ->where('status', '!=', 'cancelled')
                ->get();

            $events = [];
            foreach ($bookings as $booking) {
                $events[] = [
                    'title' => 'Appointment with ' . $booking->name,
                    'start' => $booking->appointment_date,
                    'end' => Carbon::parse($booking->appointment_date)->addMinutes($booking->duration),
                ];
            }

            return view('User.show', compact('events'));
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function edit($id)
    {
        $booking = Booking::findOrFail($id);
        $availableSlots = AppointmentSlot::all();
        return view('User.edit', compact('booking', 'availableSlots'));
    }

    public function update($id, Request $request)
    {
        $booking = Booking::findOrFail($id);
    
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }
    
        $rules = [
            'name' => 'required|max:255',
            'appointment_date' => 'required|date|after:now|unique:bookings,appointment_date,' . $booking->id,
            'phone' => 'required|digits:10',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'duration' => 'required|integer|min:15',
            'description' => 'nullable|string|max:255',
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return redirect()->route('appointment.edit', $id)->withErrors($validator)->withInput();
        }
    
        $appointmentStart = Carbon::parse($request->appointment_date);
        $requestedDuration = (int) $request->duration;
        $appointmentEnd = $appointmentStart->copy()->addMinutes($requestedDuration);
    
        $overlappingBooking = Booking::where('appointment_date', '<', $appointmentEnd)
                                    ->where('end_time', '>', $appointmentStart)
                                    ->where('id', '!=', $id)
                                    ->exists();
    
        if ($overlappingBooking) {
            $nearestSlot = $this->findNearestAvailableSlot($appointmentStart, $requestedDuration);
    
            if (!$nearestSlot) {
                return redirect()->route('appointment.edit', $id)->with('error', 'Booking cannot be made. There is already an overlapping booking and no available slots afterwards.');
            }
    
            return redirect()->route('appointment.edit', $id)->with('error', 'Booking cannot be made. There is already an overlapping booking. Nearest available slot: ' . $nearestSlot->start_time . ' - ' . $nearestSlot->end_time);
        }
    
        $booking->name = $request->name;
        $booking->appointment_date = $appointmentStart;
        $booking->duration = $requestedDuration;
        $booking->phone = $request->phone;
        $booking->status = $request->status;
        $booking->description = $request->description;
        $booking->end_time = $appointmentEnd;
        $booking->save();
    
        return redirect()->route('bookings.appointment')->with('success', 'Appointment updated successfully.');
    }

    // public function destroy($id)
    // {
    //     $booking = Booking::findOrFail($id);
    //     $booking->delete();

    //     $slot = AppointmentSlot::findOrFail($booking->slot_id);
    //     $slot->available = true;
    //     $slot->save();

    //     $booking->notifyWaitlist();

    //     return redirect()->route('bookings.dashboard')->with('success', 'Appointment deleted successfully.');
    // }


    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);

        // Check user authorization (unchanged)

        // Fetch users on the waitlist (if any)
        $waitlist = json_decode($booking->waitlist, true);

        // Delete the booking
        $booking->delete();

        // Notify and offer the slot to the first user on the waitlist (if available)
        if ($waitlist) {
            $firstUserId = array_shift($waitlist);
            $this->offerSlotToWaitlistUser($booking->appointment_date, $booking->duration, $firstUserId);
        }

        return redirect()->route('bookings.appointment')->with('success', 'Appointment deleted successfully.');
    }


    public function findNearestAvailableSlot($appointmentDate, $requestedDuration)
    {
        $startTime = Carbon::parse($appointmentDate)->setTime(10, 0, 0);
        $endTime = Carbon::parse($appointmentDate)->setTime(15, 0, 0);

        $slots = AppointmentSlot::where('available', true)
            ->where('start_time', '>=', $startTime)
            ->where('end_time', '<=', $endTime)
            ->orderBy('start_time')
            ->get();

        foreach ($slots as $slot) {
            $slotStartTime = Carbon::parse($slot->start_time);
            $slotEndTime = Carbon::parse($slot->end_time);

            if (!$this->checkAvailability($appointmentDate, $slotStartTime, $slotEndTime, $requestedDuration)) {
                continue;
            }

            if ($slotStartTime->greaterThanOrEqualTo($startTime) && $slotEndTime->lessThanOrEqualTo($endTime)) {
                return $slot;
            }
        }

        return null;
    }

    private function checkAvailability($appointmentDate, $slotStartTime, $slotEndTime, $requestedDuration)
    {
        $appointmentStart = Carbon::parse($appointmentDate)->setTimeFrom($slotStartTime);
        $appointmentEnd = $appointmentStart->copy()->addMinutes($requestedDuration);

        return !Booking::where('appointment_date', '<', $appointmentEnd)
            ->where('end_time', '>', $appointmentStart)
            ->exists();
    }
}

