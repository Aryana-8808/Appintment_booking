<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin</title>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        /* Basic styling for the body */
        body {
            background: #f7f8fa;
            font-family: serif;
        }

        /* Styling for the main container */
        .container {
            background-color: lightblue;
            font-family: serif;
            color: black;
        }

        /* Styling for the navbar */
        .navbar {
            background-color: lightblue;
            padding: 10px 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Styling for the navbar brand (logo/text) */
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: black;
        }

        /* Styling for navbar links */
        .nav-link {
            color: #ffffff;
        }

        /* Styling for the offcanvas (menu) header and body */
        .offcanvas-header, .offcanvas-body {
            background-color: lightblue;
            color: #ffffff;
        }

        /* Styling for dropdown menus */
        .dropdown-menu {
            background-color: lightblue;
            color: black;
        }

        /* Styling for dropdown items */
        .dropdown-item {
            color: black;
        }

        /* Hover effect for dropdown items */
        .dropdown-item:hover {
            background-color: #ff7f50;
        }

        /* Styling for cards */
        .card {
            border-radius: 0.5rem;
        }

        /* Styling for card headers */
        .card-header {
            background-color: #007bff;
            color: black;
        }

        /* Styling for card bodies */
        .card-body {
            background-color: lightblue;
        }

        /* Styling for card titles */
        .card h3 {
            font-size: 1.25rem;
            font-weight: bold;
        }

        /* Styling for the footer */
        .footer {
            background-color: #343a40;
            color: #ffffff;
            padding: 1rem 0;
            position: fixed;
            width: 100%;
            bottom: 0;
            text-align: center;
            font-size: 0.875rem;
        }

        /* Styling for the "Book Now" button */
        .book-now-btn {
            background-color: #ff7f50;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            font-size: 1rem;
            margin-right: 1rem;
            text-decoration: none;
        }

        /* Hover effect for the "Book Now" button */
        .book-now-btn:hover {
            background-color: #f28963;
            color: black;
        }

        .logout-btn {
            background-color: #ff7f50;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            font-size: 1rem;
            margin-right: 1rem;
            text-decoration: none;
        }

        .logout-btn:hover {
            background-color: #f28963;
            color: black;
        }

        .btn-custom {
            background-color: #ff7f50;
            border-color: #ff7f50;
            color: white;
            font-weight: bold;
        }

        .btn-custom:hover {
            background-color: #f06533;
            border-color: #f06533;
            color: white;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navbar at the top of the page -->
    <nav class="navbar navbar-expand-md bg-white shadow-lg bsb-navbar bsb-navbar-hover bsb-navbar-caret">
        <div class="container">
            <!-- Navbar brand (logo/text) -->
            <a class="navbar-brand" href="#">
                <strong>Hello, {{ Auth::guard('admin')->user()->name }}</strong>
            </a>
            <!-- Navbar toggler (menu button for mobile view) -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z" />
                </svg>
            </button>
            <!-- Offcanvas (menu) -->
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1">
                        <!-- Link to Doctors List -->
                        <li class="nav-item">
                            <a href="{{ route('doctors.index') }}" class="nav-link book-now-btn">Doctors</a>
                        </li>
                        <!-- "Book Now" button in the navbar -->
                        <li class="nav-item">
                            <a href="{{ route('admin.create') }}" class="nav-link book-now-btn">Book Now</a>
                        </li>
                        <!-- User account section with "Logout" button -->
                        <li class="nav-item">
                            @if(Auth::check())
                            <button class="logout-btn" onclick="window.location.href='{{ route('account.logout') }}'">Logout</button>
                            @else
                            <a class="nav-link" href="{{ route('account.login') }}">Login</a>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- Main container -->
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
                You are logged in !!
            </div>
        </div>
        <div class="card border-0 shadow my-5">
            <div class="card-header bg-light">
                <h3 class="h5 pt-2">Appointments List</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            {{-- <th>Id</th> --}}
                            <th>Name</th>
                            <th>Appointment Date</th>
                            <th>Phone</th>
                            <th>Duration (in minutes)</th>
                            <th>Status</th>
                            <th>Description</th>
                            <th>Modify Appointment</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($appointments != NULL)
                        @forelse ($appointments as $appointment)
                        <tr>
                            {{-- <td>{{ $appointment->id }}</td> --}}
                            <td>{{ $appointment->name }}</td>
                            <td>{{ $appointment->appointment_date }}</td>
                            <td>{{ $appointment->phone }}</td>
                            <td>{{ $appointment->duration }} minutes</td>
                            <td>{{ $appointment->status }}</td>
                            <td>{{ $appointment->description }}</td>
                            <td>
                                <a href="{{ route('admin.appointments.edit', $appointment->id) }}" class="btn btn-custom">Edit</a>
                                <a href="#" onclick="deleteAppointment({{ $appointment->id }})" class="btn btn-danger">Cancel</a>
                                <form id="delete-appointment-form-{{ $appointment->id }}" action="{{ route('admin.appointments.destroy', $appointment->id )}}" method="post" style="display: none;">
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
    <!-- Footer at the bottom of the page -->
    <footer class="footer">
        <div class="container">
            &copy; {{ date('Y') }} Doctor-Patient. All rights reserved.
        </div>
    </footer>
    <script>
        function deleteAppointment(appointmentId) {
            if (confirm('Are you sure you want to cancel this appointment?')) {
                document.getElementById(`delete-appointment-form-${appointmentId}`).submit();
            }
        }
    </script>
    <script src="https://unpkg.com/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
