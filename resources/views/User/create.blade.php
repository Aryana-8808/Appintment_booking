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
        .available-slot {
            margin-top: 10px;
        }
        .text-danger {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container form-container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Create New Appointment</h3>
            </div>
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <div class="card-body">
                <form action="{{ route('bookings.store') }}" method="post" id="appointmentForm">
                    @csrf
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                        <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="slot_id">Select Time Slot</label>
                        <select class="form-control @error('slot_id') is-invalid @enderror" id="slot_id" name="slot_id" required>
                            @foreach($availableSlots as $slot)
                                @php
                                    $class = $slot->available ? '' : 'unavailable';
                                    if (session('error') && isset($slot->id) && $slot->id == old('slot_id')) {
                                        $class .= ' text-danger'; // Add red color for suggested time slot
                                    }
                                @endphp
                                <option value="{{ $slot->id }}" data-start="{{ $slot->start_time }}" data-end="{{ $slot->end_time }}" class="{{ $class }}">
                                    {{ $slot->start_time }} - {{ $slot->end_time }}
                                </option>
                            @endforeach
                        </select>
                        @error('slot_id')
                        <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="appointment_date">Appointment Date</label>
                        <input type="datetime-local" class="form-control @error('appointment_date') is-invalid @enderror" id="appointment_date" name="appointment_date" value="{{ old('appointment_date') }}" required>
                        @error('appointment_date')
                        <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>

                    

                    <div class="form-group">
                        <label for="duration">Duration (minutes)</label>
                        <input type="number" class="form-control @error('duration') is-invalid @enderror" id="duration" name="duration" value="{{ old('duration') }}" required>
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
                    <input type="hidden" name="user_id" value="{{ $userId }}">
                    

                    <div class="form-group">
                        <label for="description">Description (optional)</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    </div>
                    @if (session('error'))
                    <button type="submit" class="btn btn-custom">Join Waitlist</button>
                    @else
                    <button type="submit" class="btn btn-custom">Create Appointment</button>
                @endif
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#slot_id').change(function () {
                var startTime = $(this).find(':selected').data('start');
                var endTime = $(this).find(':selected').data('end');

                $('#appointment_date').attr('min', startTime);
                $('#appointment_date').attr('max', endTime);

                // Reset appointment date if it's not within the new range
                var selectedDateTime = $('#appointment_date').val();
                if (selectedDateTime < startTime || selectedDateTime > endTime) {
                    $('#appointment_date').val('');
                }
            });
        });
    </script>
</body>
</html>
