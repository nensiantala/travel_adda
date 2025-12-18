<?php include "header3.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>About Us - Travel Adda</title>
  <link rel="stylesheet" href="style/style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
  margin: 0;
  font-family: 'Nunito Sans', sans-serif;
  background-color: #ffffff; /* white background */
  color: #000;
  overflow-x: hidden;
}

p, h1, h2, h3, h4, h5, h6 {
  color: #222;
}

.section-title {
  color: #17B978;
  font-weight: 700;
  text-transform: uppercase;
  margin-bottom: 40px;
  position: relative;
  animation: fadeInUp 1s ease-out;
}

.about-modern {
  background: #f9f9f9;
}

.about-text h1 {
  font-size: 2.5rem;
  color: #17B978;
}

.about-text h6 {
  color: #138d75;
  font-weight: bold;
  letter-spacing: 1px;
}

.about-text p {
  color: #555;
}

.mission-vision-section {
  background-color: #ffffff;
}

.mission-vision-section .card {
  transition: transform 0.4s ease, box-shadow 0.4s ease;
  border-left: 5px solid #17B978;
}

.mission-vision-section .card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 20px rgba(23, 185, 120, 0.1);
}

/* Animations */
@keyframes fadeLeft {
  from { opacity: 0; transform: translateX(-50px); }
  to { opacity: 1; transform: translateX(0); }
}
@keyframes zoomInImage {
  from { transform: scale(0.9); opacity: 0; }
  to { transform: scale(1); opacity: 1; }
}
@keyframes slideUp {
  from { opacity: 0; transform: translateY(50px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-fade-left {
  animation: fadeLeft 1s ease forwards;
}
.animate-zoom-in {
  animation: zoomInImage 1.2s ease forwards;
}
.animate-slide-up {
  animation: slideUp 1.2s ease forwards;
}
.animate-slide-up.delay-1 {
  animation-delay: 0.3s;
}

/* About Container */
.about-container {
  /* padding: 60px 30px; */
  animation: fadeIn 2s ease-in-out;
  background-color: #ffffff;
}

.about-container h1 {
  font-size: 3em;
  text-align: center;
  animation: slideDown 1.5s ease;
}

.underline {
  width: 80px;
  height: 4px;
  background: #17B978;
  margin: 10px auto 30px auto;
  animation: growLine 2s ease-out;
}

.about-content {
  max-width: 1000px;
  margin: auto;
  background-color: #ffffff;
  border-radius: 15px;
  padding: 40px;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
  animation: floatUp 2.5s ease;
  color: #222;
}

/* Highlights */
p {
  line-height: 1.8;
  font-size: 1.2em;
  color: #333;
}
.container p {
  color: #333;
}
.highlights {
  margin-top: 30px;
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  justify-content: space-around;
}
.highlight-box {
  flex: 1 1 220px;
  background: linear-gradient(to bottom right,rgba(226, 226, 226, 0.6),rgba(126, 126, 126, 0.59));
  padding: 20px;
  border-radius: 10px;
  text-align: center;
  transition: transform 0.3s ease;
  animation: bounceUp 3s ease infinite alternate;
  color: #222;
}
.highlight-box:hover {
  transform: scale(1.05);
  background-color: #e9e9e9;
}
.highlight-box h3 {
  margin-bottom: 10px;
  color: #17B978;
}

/* Stats Section */
.stat-box {
  background-color: #f5f5f5;
  border: 2px solid #17B978;
  padding: 25px;
  border-radius: 12px;
  text-align: center;
  margin-bottom: 30px;
  transition: transform 0.3s ease;
  animation: fadeInUp 1s ease forwards;
  color: #222;
}
.stat-box h2 {
  color: #17B978;
  font-size: 40px;
  font-weight: bold;
}
.stat-box:hover {
  transform: translateY(-8px) scale(1.03);
  box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
}

/* Mission Boxes */
/* .mission-box, .container .row > div {
  background-color: #ffffff;
  color: #222;
  border-left: 5px solid #17B978;
  padding: 25px;
  border-radius: 10px;
  margin-bottom: 30px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
} */
.mission-vision-simple {
  background-color: #f9f9f9;
}

.mission-vision-simple .border-success {
  border-color: #17B978 !important;
}

.mission-vision-simple h4 {
  font-size: 1.3rem;
}


/* Team */
.team img {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid #17B978;
  transition: transform 0.4s ease;
}
.team img:hover {
  transform: scale(1.1) rotate(-2deg);
}
.team h5 {
  margin-top: 15px;
  color: #222;
  font-weight: 600;
}
.team p {
  color: #555;
}

/* Remove Bootstrap btn look */
.navbar .btn,
.navbar .btn-outline-primary,
.navbar .btn-light {
  background: none !important;
  border: none !important;
  color: #666 !important;
  padding: 0;
}

.navbar .btn:hover {
  color: #17B978 !important;
}

.btn-outline-primary {
  border: 2px solid var(--primary);
  color: var(--primary);
  transition: 0.3s;
}

.btn-outline-primary:hover {
  background-color: var(--primary);
  color: var(--white);
}
/* Animations */
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}
@keyframes slideDown {
  from { transform: translateY(-50px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}
@keyframes growLine {
  from { width: 0; }
  to { width: 80px; }
}
@keyframes floatUp {
  from { transform: translateY(60px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}
@keyframes bounceUp {
  0% { transform: translateY(0); }
  100% { transform: translateY(-8px); }
}
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}
@keyframes fadeInDown {
  from { opacity: 0; transform: translateY(-30px); }
  to { opacity: 1; transform: translateY(0); }
}
@keyframes zoomIn {
  from { transform: scale(0.9); opacity: 0; }
  to { transform: scale(1); opacity: 1; }
}

/* Animation Delays */
.delay-1 { animation-delay: 0.2s; }
.delay-2 { animation-delay: 0.4s; }
.delay-3 { animation-delay: 0.6s; }


  </style>
</head>
<body>

<!-- New About Section -->
<section class="about-modern py-5">
  <div class="container">
    <div class="row align-items-center g-4"> <!-- g-4 for proper spacing -->

      <!-- Text First -->
      <div class="col-md-6">
        <div class="about-text">
          <h6 class="text-uppercase mb-3">About Us</h6>
          <h1 class="fw-bold mb-3 text-success">Helping travelers explore the world with confidence</h1>
          <p class="lead text-muted">
            At Travel Adda, our mission is to make every trip unforgettable. We offer curated experiences,
            seamless booking, and unbeatable support — because you deserve a journey as amazing as your destination.
          </p>
          <a href="packages.php" class="btn btn-success mt-3 px-4 py-2">Explore Packages</a>
        </div>
      </div>

      <!-- Image Second -->
      <div class="col-md-5 text-center">
        <img src="image/pexels-fauxels-3184306.jpg" class="img-fluid rounded shadow" alt="Travel Adda" style="max-height: 350px; width: 100%; object-fit: cover;">
      </div>

    </div>
  </div>
</section>


<div class="about-container">
    <h1>About Travel Adda</h1>
    <div class="underline"></div>

    <div class="about-content">
        <p>At <strong>Travel Adda</strong>, we believe in transforming journeys into unforgettable adventures. Our platform is built to help travelers explore the world with ease and confidence. Whether you're a solo wanderer, a family planner, or a group adventurer, we have personalized packages to suit every dream and budget.</p>

        <p>With a seamless booking process, curated destinations, and exceptional support, we’re more than just a travel site — we’re your next journey's best friend.</p>

        <div class="highlights">
            <div class="highlight-box">
                <h3>5000+ Happy Travelers</h3>
                <p>Trusted by thousands of satisfied adventurers.</p>
            </div>
            <div class="highlight-box">
                <h3>24/7 Support</h3>
                <p>Our team is always here to assist you.</p>
            </div>
            <div class="highlight-box">
                <h3>Best Price Guarantee</h3>
                <p>Competitive pricing on all travel packages.</p>
            </div>
            <div class="highlight-box">
                <h3>Customized Itineraries</h3>
                <p>Plan trips tailored to your interests and pace.</p>
            </div>
        </div>
    </div>
</div>

<!-- Who We Are -->
<div class="container py-5">
  <h2 class="text-center section-title">Who We Are</h2>
  <p class="text-center w-75 mx-auto">
    Travel Adda is a passionate team of travel enthusiasts dedicated to offering incredible travel packages across the world. We focus on delivering a seamless and memorable experience, whether it’s a relaxing beach holiday or an adventurous trek through the mountains.
  </p>
</div>

<!-- Stats Section -->
<div class="container py-4">
  <div class="row text-center">
    <div class="col-md-4">
      <div class="stat-box">
        <h2>500+</h2>
        <p>Happy Customers</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stat-box">
        <h2>100+</h2>
        <p>Packages Offered</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="stat-box">
        <h2>4.9★</h2>
        <p>Average Rating</p>
      </div>
    </div>
  </div>
</div>

<!-- Modern Mission & Vision -->
<section class="mission-vision-simple py-4">
  <div class="container">
    <div class="row">
      <div class="col-md-5 mb-3">
        <div class="p-4 bg-white border-start border-4 border-success rounded shadow-sm h-100">
          <h4 class="text-success fw-bold mb-2">Our Mission</h4>
          <p class="mb-0">
            To inspire travelers to discover new horizons by providing flexible, affordable, and reliable travel solutions.
          </p>
        </div>
      </div>
      <div class="col-md-5 mb-3">
        <div class="p-4 bg-white border-start border-4 border-success rounded shadow-sm h-100">
          <h4 class="text-success fw-bold mb-2">Our Vision</h4>
          <p class="mb-0">
            To become India’s most loved travel brand through personalized experiences and excellent customer service.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>



<!-- Meet Our Team -->
<div class="container py-5 team text-center">
  <h2 class="section-title">Meet Our Team</h2>
  <div class="row justify-content-center">
    <div class="col-md-4">
      <img src="image/woman.avif" alt="Founder">
      <h5 class="mt-3">Nensi Antala</h5>
      <p>Founder & CEO</p>
    </div>
    
    <div class="col-md-4">
      <img src="image/man.jpeg" alt="Support">
      <h5 class="mt-3">Darshit Maheta</h5>
      <p>Head Manager</p>
    </div>
  </div>
</div>

</body>
</html>
<?php include "footer.php"; ?>
