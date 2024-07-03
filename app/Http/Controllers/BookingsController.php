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

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        $slot = AppointmentSlot::findOrFail($booking->slot_id);
        $slot->available = true;
        $slot->save();

        $booking->notifyWaitlist();

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
