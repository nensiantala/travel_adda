<?php include "header3.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Contact Us - Travel Adda</title>
  <link rel="stylesheet" href="style/style.css" />
  <style>
    /* Reset + Font */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}
body {
  font-family: 'Inter', sans-serif;
  background: #f9f9f9;
  color: #333;
  line-height: 1.6;
}

/* Hero Section */
.hero {
  background: linear-gradient(to right, #17B978, #08a88a);
  padding: 60px 20px;
  color: white;
  text-align: center;
}
.hero-content h1 {
  font-size: 36px;
  margin-bottom: 10px;
}
.hero-content p {
  font-size: 18px;
  opacity: 0.9;
  max-width: 600px;
  margin: auto;
}

/* Contact Section */
.contact-section {
  padding: 50px 20px;
  display: flex;
  justify-content: center;
}
.contact-box {
  display: flex;
  flex-wrap: wrap;
  max-width: 1100px;
  background: white;
  border-radius: 15px;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
  overflow: hidden;
}

/* Form Section */
.form-section {
  flex: 1 1 60%;
  padding: 40px;
}
.form-section h2 {
  font-size: 28px;
  margin-bottom: 25px;
  color: #17B978;
}
.form-section input,
.form-section textarea {
  width: 100%;
  padding: 14px 20px;
  margin-bottom: 20px;
  border-radius: 10px;
  border: 1px solid #ccc;
  font-size: 16px;
}
.form-section button {
  width: 100%;
  padding: 14px;
  background: #17B978;
  color: white;
  border: none;
  font-size: 16px;
  border-radius: 30px;
  cursor: pointer;
  transition: background 0.3s ease;
}
.form-section button:hover {
  background: #129e6c;
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
/* Info Section */
.info-section {
  flex: 1 1 40%;
  background: #f4f7f9;
  padding: 40px;
}
.info-section h3 {
  font-size: 22px;
  margin-bottom: 20px;
}
.info-section p,
.info-section h4 {
  margin-bottom: 15px;
  font-size: 16px;
}
.info-section i {
  margin-right: 10px;
  color: #17B978;
}
.location {
  margin-top: 20px;
}

/* Responsive */
@media (max-width: 768px) {
  .contact-box {
    flex-direction: column;
  }
  .form-section, .info-section {
    flex: 1 1 100%;
    padding: 30px 20px;
  }
}
  </style>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
</head>
<body>

<header class="hero">
  <div class="hero-content">
    <h1>We’d love to hear from you</h1>
    <p>Whether you're curious about features, a free trial, or even press—we’re ready to answer any and all questions.</p>
  </div>
</header>

<section class="contact-section">
  <div class="contact-box">

    <!-- Contact Form -->
    <div class="form-section">
      <h2>Get in Touch</h2>
      <form method="POST" action="">
        <input type="text" name="name" placeholder="Your Name" required>
        <input type="email" name="email" placeholder="Your Email Address" required>
        <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
        <button type="submit">Send Message</button>
      </form>
    </div>

    <!-- Info Section -->
    <div class="info-section">
      <h3>Contact Information</h3>
      <p><i class="fas fa-envelope"></i> support@traveladda.com</p>
      <p><i class="fas fa-phone"></i> +91 9876543210</p>
      <div class="location">
        <h4><i class="fas fa-location-dot"></i> Travel Adda India</h4>
        <p>B-222, Tech Park, Okhla Industrial Area, New Delhi - 110020</p>
      </div>
      <div class="location">
        <h4><i class="fas fa-location-dot"></i> Travel Adda USA</h4>
        <p>501 Silverside Road, Suite 105, Wilmington, DE 19809</p>
      </div>
    </div>

  </div>
</section>

</body>
</html>

<!-- Footer -->
<?php include('footer.php'); ?>
