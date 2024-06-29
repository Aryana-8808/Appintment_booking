{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Appointment</title>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.6/main.min.css' rel='stylesheet' />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .form-container {
            margin-top: 50px;
        }
        .card-header {
            background-color: lightblue;
            color: white;
        }
        .btn-custom {
            background-color: #ff7f50;
            color: white;
        }
        #calendar {
            margin-top: 20px;
        }
        .available-slot {
            margin-top: 20px;
            border: 1px solid #ddd;
            padding: 10px;
            background-color: #5cb85c; /* Customize background color for available slots */
            color: #fff; /* Customize text color for available slots */
        }
        .booked-slot {
            background-color: #f0ad4e; /* Customize background color for booked slots */
            border-color: #eea236; /* Customize border color for booked slots */
            color: #fff; /* Customize text color for booked slots */
            margin-top: 20px;
            padding: 10px;
        }
        .sidenav {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container form-container">
        <div id="calendar"></div>

        <div class="sidenav">
            <h3>Booked Appointments</h3>
            <ul>
                @foreach ($bookings as $booking)
                    @if ($booking->status !== 'cancelled')
                        <li>{{ $booking->name }} - {{ $booking->appointment_date }} ({{ $booking->duration }} minutes)</li>
                    @endif
                @endforeach
            </ul>
    
            <h3>Cancelled Appointments</h3>
            <ul>
                @foreach ($bookings as $booking)
                    @if ($booking->status === 'cancelled')
                        <li>{{ $booking->name }} - {{ $booking->appointment_date }} ({{ $booking->duration }} minutes)</li>
                    @endif
                @endforeach
            </ul>
    
            <div class="available-slot">
                <h4>Available Appointment Slots</h4>
                @foreach ($availableSlots as $slot)
                    <div>
                        <span>{{ $slot->start_time->format('Y-m-d H:i') }}</span>
                        <button class="btn btn-sm btn-primary" onclick="selectSlot({{ $slot->id }})">Book Now</button>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="container form-container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Create New Appointment</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('bookings.store') }}" method="post">
                    @csrf
                    <input type="hidden" id="slot_id" name="slot_id" value="">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" required>
                        @error('name')
                        <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="appointment_date">Appointment Date</label>
                        <input type="datetime-local" class="form-control @error('appointment_date') is-invalid @enderror" id="appointment_date" name="appointment_date" required>
                        @error('appointment_date')
                        <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="duration">Duration</label>
                        <select class="form-control @error('duration') is-invalid @enderror" id="duration" name="duration" required>
                            <option value="30">30 minutes</option>
                            <option value="60">1 hour</option>
                            <option value="90">1.5 hours</option>
                            <option value="120">2 hours</option>
                        </select>
                        @error('duration')
                        <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" pattern="[0-9]{10,}" placeholder="Phone number" value="{{ old('phone') }}" required>
                        @error('phone')
                        <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                        @error('status')
                        <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="reason">Reason</label>
                        <input type="text" class="form-control" id="reason" name="reason">
                    </div>
                    <button type="submit" class="btn btn-custom">Create Appointment</button>
                </form>
            </div>
        </div>
    </div>

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.6/main.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const bookings = @json($bookings);
            const availableSlots = @json($availableSlots);

            let calendarEl = document.getElementById('calendar');
            let calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: [
                    ...bookings.map(booking => ({
                        title: booking.name,
                        start: booking.appointment_date,
                        end: new Date(new Date(booking.appointment_date).getTime() + booking.duration * 60000),
                        color: 'red',
                        classNames: 'booked-slot',
                    })), 
                    ...availableSlots.map(slot => ({
                        title: 'Available Slot',
                        start: slot.start,
                        end: slot.end,
                        color: 'green',
                        classNames: 'available-slot',
                    }))
                ],
                selectable: true,
                select: function (info) {
                    document.getElementById('appointment_date').value = info.startStr;
                }
            });

            calendar.render();
        });

        function selectSlot(slotId) {
            document.getElementById('slot_id').value = slotId;
            // Additional logic to handle slot selection if needed
            console.log(slotId);
        }

        
    </script>
</body>
</html> --}}




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Appointment</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .form-container {
            margin-top: 50px;
        }
        .card-header {
            background-color: lightblue;
            color: white;
        }
        .btn-custom {
            background-color: #ff7f50;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container form-container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Create New Appointment</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('bookings.store') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" required>
                        @error('name')
                        <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="appointment_date">Appointment Date</label>
                        <input type="datetime-local" class="form-control @error('appointment_date') is-invalid @enderror" id="appointment_date" name="appointment_date" required>
                        @error('appointment_date')
                        <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" pattern="[0-9]{10,}" placeholder="Phone number" value="{{ old('phone') }}" required>
                        @error('phone')
                        <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>

                        @error('status')
                        <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="reason">Reason</label>
                        <input type="text" class="form-control" id="reason" name="reason">
                    </div>
                    <button type="submit" class="btn btn-custom">Create Appointment</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.6/main.min.js'></script>
