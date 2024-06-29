<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Schedule Your Appointment Today!</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    html, body {
      height: 100vh;
      margin: 0;
      padding: 0;
      font-family: serif;
    }
    .navbar {
      background-color: lightblue;
      padding: 10px 20px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }
    .navbar a {
      color: white;
      text-decoration: none;
      font-weight: bold;
      padding: 10px 15px;
      font-size: 23px; /* Change the font size here */
    }
    .navbar a:hover {
      background-color: #ff7f50;
    }

    .hero-section {
      position: relative;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      height: 93vh;
      color: #ff7f50;
      padding: 20px;
      z-index: 1;
    }
    
    .hero-section::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: url('/images/bg.jpg');
      background-repeat: no-repeat;
      background-position: center;
      background-size: cover;
      opacity: 0.5; /* Adjust the opacity to your preference */
      z-index: -1;
    }
    .hero-title {
      font-size: 4rem;
      margin-bottom: 20px;
    }
    .hero-description {
      font-size: 1.5rem;
      margin-bottom: 40px;
    }
    .btn-primary {
      background-color: #ff7f50;
      border-color: #ff7f50;
    }
    .info-section {
      padding: 50px 20px;
      background-color: #f8f9fa;
    }
    .info-section h2 {
      text-align: center;
      margin-bottom: 30px;
    }
    .info-card {
      background: white;
      border: none;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      margin: 10px;
      border-radius: 10px;
      overflow: hidden;
      justify-content: center;
      transition: transform 0.3s;
    }
    .info-card {
      background: white;
      border: none;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      margin: 10px;
      border-radius: 10px;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      align-items: center;
      transition: transform 0.3s;
      
    }
    .info-card:hover {
      transform: translateY(-10px);
    }
    .info-card img {
      width: 100%;
      height: 200px;
      object-fit: contain;
    }
    .info-card-body {
      padding: 20px;
    }
    .footer {
      background-color: #343a40;
      color: white;
      text-align: center;
      padding: 20px;
      position: relative;
      bottom: 0;
      width: 100%;
    }
  </style>
</head>
<body>
  <nav class="navbar">
    <div class="container">
      <a href="#">Home</a>
      <a href="#about">About Us</a>
      <a href="#contact">Contact</a>
      <a href="{{ route('account.login') }}">Login </a>
    </div>
  </nav>

  <section class="hero-section">
    <h1 class="hero-title">Schedule Your Appointment Today!</h1>
    <p class="hero-description"><b>Avoid the hassle and book your appointment with just one click! Experience convenience like never before.</b></p>
    <a href="{{ route('account.login') }}" class="btn btn-primary btn-lg">Book Now</a>
  </section>

  <section class="info-section" id="about">
    <div class="container">
      <h2>About Us</h2>
      <div class="row">
        <div class="col-md-4">
          <div class="card info-card">
            <img src="/images/clinic.avif" alt="Clinic">
            <div class="card-body info-card-body">
              <h5 class="card-title">Our Clinic</h5>
              <p class="card-text">State-of-the-art facilities with experienced doctors and staff.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card info-card">
            <img src="/images/tests.jpg" alt="Tests">
            <div class="card-body info-card-body">
              <h5 class="card-title">Medical Tests</h5>
              <p class="card-text">Discover the wide range of tests we offer at its best price.</p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card info-card">
            <img src="/images/healthservice.jpg" alt="Health">
            <div class="card-body info-card-body">
              <h5 class="card-title">Health Services</h5>
              <p class="card-text">Comprehensive health services for you and your family.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <footer class="footer" id="contact">
    <p>&copy; 2024 Doctor's Appointment System. All rights reserved.</p>
  </footer>

  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
