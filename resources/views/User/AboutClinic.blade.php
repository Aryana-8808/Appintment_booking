<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      font-family: serif;
      margin: 0;
      padding: 0;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
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
      font-size: 23px;
    }
    .navbar a:hover {
        border-radius: 10%;
      background-color: #ff7f50;
    }
    .info-section {
      padding: 50px 20px;
      background-color: #f8f9fa;
      flex: 1;
    }
    .info-section h2 {
      text-align: center;
      margin-bottom: 30px;
      color: #ff7f50;
    }
    .info-card {
      background: white;
      border: none;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      margin: 10px;
      border-radius: 10px;
      overflow: hidden;
      transition: transform 0.3s;
    }
    .info-card:hover {
      transform: translateY(-10px);
    }
    .info-card-body {
      padding: 20px;
      
    }
    .info-card-title {
      font-size: 1.5rem;
      color: #343a40;
    }
    .info-card-text {
      font-size: 1rem;
      color: #6c757d;
    }
    .info-card img {
      width: 200%;
      height: auto;
      max-width: 800px; /* Set max-width for the image */
      margin: 20px auto 0 auto; /* Center the image */
      display: block; /* Ensure the image is block-level */
    }

    .contact-form {
      padding: 50px 20px;
      background-color: #ffffff; /* White background color */
      margin-bottom: 20px; /* Add margin-bottom for spacing */
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Add shadow for better separation */
      border-radius: 10px; /* Rounded corners */
      background-color: #f0f0f0; /* Light background color for contact form */
    }
    .contact-form h2 {
      text-align: center;
      
      margin-bottom: 30px;
      color: #ff7f50;
    }

    .slogan {
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 1.2rem;
      /* color: #6c757d; */
      padding: 20px;
    }
    .form-group label {
      color: #343a40;
    }
    .btn-primary {
      background-color: #ff7f50;
      border-color: #ff7f50;
    }
    .btn-primary:hover {
      background-color: #e5673d;
      border-color: #e5673d;
    }


    .footer {
      background-color: #343a40;
      color: white;
      text-align: center;
      padding: 20px;
      position: relative;
      width: 100%;
    }
  </style>
</head>
<body>
  <nav class="navbar">
    <div class="container">
      <a href="{{ route('bookings.index') }}">Home</a>
      <a href="#about">About Us</a>
      <a href="#contact">Contact</a>
      <a href="{{ route('account.login') }}">Login</a>
    </div>
  </nav>

  <section class="info-section" id="about">
    <div class="container">
      <h2>About Us</h2>
      <div class="row">
        <div class="col-md-12">
          <div class="card info-card">
            <h1 style="text-align: center; color: #ff7f50;">Welcome to The CalenDoc</h1>
            <img src="/images/AboutClinic.avif" alt="Our Clinic">
            <div class="card-body info-card-body">
              <h3 class="info-card-title" style="text-color: black;">Our Clinic</h3>
              <p class="info-card-text" style="color: black;">State-of-the-art facilities with experienced doctors and staff. At CalenDoc, experience cutting-edge care with state-of-the-art facilities, a team of experienced doctors and staff, comprehensive health services encompassing routine checkups to specialized treatments, and extensive medical testing for accurate diagnoses – all under one roof for a seamless and personalized healthcare journey.</p>
              <h3>Taking Care of You: A Range of Comprehensive Services</h3>
              <p>At CalenDoc, we understand that your health needs are unique. That's why we offer a wide range of services designed to address your individual concerns and keep you feeling your best.</p>
              <p>From routine check-ups to specialized treatments, our team of experienced healthcare professionals is dedicated to provide our patient with the best care!</p>
              <h3>Here's what we offer:</h3>
              <p>We offer a wide range of services to address your individual needs:</p>

              <ul>
                <li>General Checkups: Schedule regular checkups to stay on top of your health and identify any potential issues early on.</li>
                <li>Preventative Care: We believe prevention is key. We offer services like immunizations, screenings, and lifestyle counseling to help you stay healthy and avoid future problems.</li>
                <li>Chronic Disease Management: Living with a chronic condition can be challenging. We offer comprehensive management plans to help you control your condition and improve your quality of life.</li>
              </ul>
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="contact-form" id="contact">
    <div class="container">
      <h2>Contact Us</h2>
      <div class="row">
        <div class="col-md-6">
          <div class="slogan">
            <p>Your health is our priority. Reach out to us for world-class medical care.</p>
            
          </div>
        </div>
        <div class="col-md-6">
          <form>
            <div class="form-group">
              <label for="name">Name</label>
              <input type="text" class="form-control" id="name" placeholder="Enter your name">
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" class="form-control" id="email" placeholder="Enter your email">
            </div>
            <div class="form-group">
              <label for="message">Message</label>
              <textarea class="form-control" id="message" rows="4" placeholder="Enter your message"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
          </form>
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
