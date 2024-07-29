<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>User Dashboard</title>
   <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css">
   <style>
       body {
           background: #f7f8fa;
           font-family: serif;
       }

       .container {
           background-color: lightblue;
           font-family: serif;
           color: black;
       }

       .navbar {
           background-color: lightblue;
           padding: 10px 20px;
           box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
       }

       .navbar-brand {
           font-size: 1.5rem;
           font-weight: bold;
           color: black;
       }

       .nav-link, .book-now-btn, .logout-btn, .doctor-btn {
           color: white;
       }

       .offcanvas-header, .offcanvas-body {
           background-color: lightblue;
           color: #fff;
       }

       .dropdown-menu {
           background-color: lightblue;
           color: black;
       }

       .dropdown-item {
           color: black;
       }

       .dropdown-item:hover {
           background-color: #ff7f50;
       }

       .card {
           border-radius: 0.5rem;
       }

       .card-header {
           background-color: #007bff;
           color: black;
       }

       .card-body {
           background-color: lightblue;
       }

       .card h3 {
           font-size: 1.25rem;
           font-weight: bold;
       }

       .footer {
           background-color: #343a40;
           color: #fff;
           padding: 1rem 0;
           position: fixed;
           width: 100%;
           bottom: 0;
           text-align: center;
           font-size: 0.875rem;
       }

       .book-now-btn, .logout-btn, .doctor-btn, .btn-custom {
           background-color: #ff7f50;
           color: white;
           border: none;
           padding: 0.5rem 1rem;
           border-radius: 0.25rem;
           font-size: 1rem;
           margin-right: 1rem;
           text-decoration: none;
       }

       .book-now-btn:hover, .logout-btn:hover, .doctor-btn:hover, .btn-custom:hover {
           background-color: #f28963;
           color: black;
       }
   </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-md bg-white shadow-lg">
        <div class="container">
            <a class="navbar-brand" href="#">
               <strong>Hello, {{ Auth::user()->name }}</strong>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z" />
                </svg>
            </button>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1">
                        <li class="nav-item">
                            <a href="{{ route('bookings.create') }}" class="btn book-now-btn">Book Now</a>
                        </li>
                        <li class="nav-item">
                            @if(Auth::check())
                                <a class="btn logout-btn" href="{{ route('account.logout') }}">Logout</a>
                            @else
                                <a class="nav-link" href="{{ route('account.login') }}">Login</a>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row d-flex justify-content-center">
            @if(session()->has('success'))
                <div class="col-md-10">
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                </div>
            @endif
        </div>

        <div class="card border-0 shadow my-5">
            <div class="card-header bg-light">
                <h3 class="h5 pt-2">Dashboard</h3>
            </div>
            <div class="card-body">
                You are logged in!!
            </div>
        </div>

        <div class="card border-0 shadow my-5">
            <div class="card-header bg-light">
                <h3 class="h5 pt-2">Appointments List</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Appointment Date</th>
                            <th>Phone</th>
                            <th>Duration</th>
                            <th>Status</th>
                            {{-- <th>Description</th> --}}
                            <th>Modify Appointment</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($appointments)
                            @forelse ($appointments as $appointment)
                                <tr>
                                    <td>{{ $appointment->id }}</td>
                                    <td>{{ $appointment->name }}</td>
                                    <td>{{ $appointment->appointment_date }}</td>
                                    <td>{{ $appointment->phone }}</td>
                                    <td>{{ $appointment->duration }}</td>
                                    {{-- <td>{{ $appointment->status }}</td> --}}
                                    <td>{{ $appointment->description }}</td>
                                    <td>
                                        <a href="{{ route('appointment.edit', $appointment->id) }}" class="btn btn-custom">Edit</a>
                                        <a href="#" onclick="deleteAppointment({{ $appointment->id }})" class="btn btn-danger">Cancel</a>
                                        <form id="delete-product-form-{{ $appointment->id }}" action="{{ route('appointment.destroy', $appointment->id) }}" method="post" style="display: none;"> 
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No appointments found.</td>
                                </tr>
                            @endforelse
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <footer class="footer">
        © 2024 Appointment Booking System. All rights reserved.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function deleteAppointment(id) {
            if (confirm('Are you sure you want to delete this appointment?')) {
                document.getElementById("delete-product-form-" + id).submit();
            }
        }
    </script>
</body>
</html>
