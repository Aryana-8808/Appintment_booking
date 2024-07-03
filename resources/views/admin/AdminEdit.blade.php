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
        .form-group {
            margin-bottom: 20px;
        }
        .btn-custom {
            background-color: #ff7f50;
            color: white;
        }
        label {
            font-weight: bold;
        }
        textarea {
            width: 100%;
            min-height: 100px;
            resize: vertical;
        }
        button[type="submit"] {
            background-color: #ff7f50;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }
        button[type="submit"]:hover {
            background-color: #e64a19;
        }
    </style>
</head>
<body>
    <div class="container form-container">
        <div class="card">
            <div class="card-header">
                <h1>Edit Appointment</h1>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.update', $booking->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ $booking->name }}" required>
                    </div>

                    <div class="form-group">
                        <label for="appointment_date">Appointment Date:</label>
                        <input type="datetime-local" id="appointment_date" name="appointment_date" class="form-control" value="{{ $booking->appointment_date ? \Carbon\Carbon::parse($booking->appointment_date)->format('Y-m-d\TH:i') : '' }}" required>
                    </div>

                    <div class="form-group">
                        <label for="duration">Duration (minutes):</label>
                        <input type="number" id="duration" name="duration" class="form-control" value="{{ $booking->duration }}" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input type="text" id="phone" name="phone" class="form-control" value="{{ $booking->phone }}" required>
                    </div>

                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select id="status" name="status" class="form-control" required>
                            <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea id="description" name="description" class="form-control">{{ $booking->description }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-custom">Update</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
