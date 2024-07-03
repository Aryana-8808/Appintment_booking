<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Appointment</title>
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
            padding: 10px;
            text-align: center;
        }
        .card-body {
            padding: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .btn-custom {
            background-color: #ff7f50;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }
        .btn-custom:hover {
            background-color: #e64a19;
        }
        label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container form-container">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Appointment</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('appointment.update', $booking->id ) }}" method="post">
                    @csrf
                    @method('put')
                   
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $booking->name) }}" required>
                        @error('name')
                            <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- <div class="form-group">
                        <label for="slot_id">Select Time Slot</label>
                        <select class="form-control @error('slot_id') is-invalid @enderror" id="slot_id" name="slot_id" required>
                            @csrf
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
                    </div> --}}

                    <div class="form-group">
                        <label for="appointment_date">Appointment Date</label>
                        <input type="datetime-local" id="appointment_date" name="appointment_date" class="form-control @error('appointment_date') is-invalid @enderror" value="{{ old('appointment_date', \Carbon\Carbon::parse($booking->appointment_date)->format('Y-m-d\TH:i')) }}" required>
                        @error('appointment_date')
                            <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="duration">Duration (minutes)</label>
                        <input type="number" id="duration" name="duration" class="form-control @error('duration') is-invalid @enderror" value="{{ old('duration', $booking->duration) }}" required>
                        @error('duration')
                            <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $booking->phone) }}" pattern="[0-9]{10,}" placeholder="Phone number" required>
                        @error('phone')
                            <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control @error('status') is-invalid @enderror" required>
                            <option value="pending" {{ old('status', $booking->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ old('status', $booking->status) == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="completed" {{ old('status', $booking->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ old('status', $booking->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        @error('status')
                            <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="form-control">{{ old('description', $booking->description) }}</textarea>
                    </div>                    

                    <button type="submit" class="btn btn-custom">Update Appointment</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
