{{-- <!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Doctors List</title>
   <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">List of Doctors</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Specialization</th>
                    <th>Contact</th>
                </tr>
            </thead>
            <tbody>
                @forelse($doctors as $doctor)
                <tr>
                    <td>{{ $doctor->id }}</td>
                    <td>{{ $doctor->name }}</td>
                    <td>{{ $doctor->specialization }}</td>
                    <td>{{ $doctor->contact }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">No doctors found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> --}}



<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Doctors List</title>
   <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css">
   <style>
       /* Custom styles for the doctors' cards */
       .card {
           margin-bottom: 20px;
       }
       .card-img-top {
           height: 200px; /* Adjust as needed */
           object-fit: cover;
       }
   </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">List of Doctors</h2>
        <div class="row">
            @forelse($doctors as $doctor)
                <div class="col-md-4">
                    <div class="card">
                        <!-- Doctor's image -->
                        <img src="{{ $doctor->image_url }}" class="card-img-top" alt="{{ $doctor->name }}">
                        <div class="card-body">
                            <!-- Doctor's details -->
                            <h5 class="card-title">{{ $doctor->name }}</h5>
                            <p class="card-text">Specialization: {{ $doctor->specialization }}</p>
                            <p class="card-text">Contact: {{ $doctor->contact }}</p>
                            <!-- Book Now button -->
                            <a href="{{ route('bookings.create', ['doctor_id' => $doctor->id]) }}" class="btn btn-primary">Book Now</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col">
                    <div class="alert alert-warning">No doctors found.</div>
                </div>
            @endforelse
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

