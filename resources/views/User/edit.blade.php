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
                <h3 class="card-title">Edit Appointment</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('appointment.update', $booking->id ) }}" method="post">
                    @method('put')
                    @csrf
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input value= "{{old('name',$booking->name)}}" type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" required>
                        @error('name')
                        <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="appointment_date">Appointment Date</label>
                        <input value="{{old('appointment_date',$booking->appointment_date)}}" type="datetime-local" class="form-control @error('appointment_date') is-invalid @enderror" id="appointment_date" name="appointment_date" required>
                        @error('appointment_date')
                        <p class="invalid-feedback">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input value="{{old('phone',$booking->phone)}}" type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" pattern="[0-9]{10,}" placeholder="Phone number" value="{{ old('phone') }}" required>
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
                        <input value="{{old('Reason',$booking->description)}}"type="text" class="form-control" id="reason" name="reason">
                    </div>
                    <button type="submit" class="btn btn-custom">Update Appointment</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
