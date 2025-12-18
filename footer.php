<style>
    
/* Footer Main Container */
.footer {
  background-color: #00435a; /* rich dark slate blue */
  color: #EEEEEE;
  padding: 60px 20px 30px;
  font-family: 'Segoe UI', sans-serif;
  margin-top: 30px;
}

/* Layout */
.footer-container {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  max-width: 1200px;
  margin: auto;
}

/* Each Column */
.footer-column {
  flex: 1 1 240px;
  margin: 20px;
  min-width: 220px;
}

/* Logo */
.footer-logo {
  width: 200px;
  margin-bottom: 15px;
  filter: brightness(1.1);
}

/* Column Titles */
.footer-column h4 {
  color: #17B978;
  font-size: 18px;
  margin-bottom: 20px;
  text-transform: uppercase;
  letter-spacing: 1px;
}

/* Links */
.footer-column ul {
  list-style: none;
  padding: 0;
}

.footer-column ul li a {
  color: #DDDDDD;
  text-decoration: none;
  margin-bottom: 10px;
  display: block;
  transition: color 0.3s ease;
}

.footer-column ul li a:hover {
  color: #17B978;
}

/* Text Blocks */
.footer-column p {
  margin-bottom: 10px;
  line-height: 1.6;
  color: #CCCCCC;
}

/* Social Icons */

.social-links a {
  font-size: 24px;
  color:rgb(255, 255, 255);
  margin-right: 15px;
  transition: color 0.3s ease;
  text-decoration: none;
}
.social-links a:hover {
  color: #138d75;
}



/* Newsletter */
.newsletter-form input[type="email"] {
  padding: 10px;
  width: 70%;
  border: none;
  border-radius: 5px;
  margin-bottom: 10px;
  font-size: 14px;
}

.newsletter-form button {
  background-color: #17b9788e;
  color: #fff;
  border: none;
  padding: 10px 16px;
  border-radius: 5px;
  cursor: pointer;
  font-size: 14px;
  transition: background 0.3s ease;
}

.newsletter-form button:hover {
  background-color: #14a96bac;
}

/* Bottom Bar */
.footer-bottom {
  border-top: 1px solid #c7b80c9b;
  text-align: center;
  padding-top: 20px;
  margin-top: 30px;
  color:rgb(255, 255, 255);
  font-size: 14px;
}
</style>

<!-- Font Awesome CSS for social media icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<footer class="footer">
  
  <div class="footer-container">
    <div class="footer-column">
      <img src="image\logo.png" alt="The Travel Adda Logo" class="footer-logo">
      <p>Your trusted travel partner for unforgettable journeys. Explore the world with Travel Adda.</p>
    </div>

    <div class="footer-column">
      <h4>Quick Links</h4>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About Us</a></li>
        <li><a href="packages.php">Tours</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="customer_login.php">Login</a></li>
      </ul>
    </div>

    <div class="footer-column">
      <h4>Contact Us</h4>
      <p>Email: info88@traveladda.com</p>
      <p>Phone: +91 98765 43210</p>
      <p>Location: Rajkot , Gujarat, India</p>
    </div>

    <div class="footer-column">
      <h4>Follow Us</h4>
      <div class="social-links">
  <a href="#" class="me-3"><i class="fab fa-facebook-f"></i></a>
  <a href="#" class="me-3"><i class="fab fa-instagram"></i></a>
  <a href="#"><i class="fab fa-youtube"></i></a>
</div>

      <form class="newsletter-form">
       
        
      </form>
    </div>
  </div>
  <div class="footer-bottom">
    <p>© 2025 The Travel Adda. All rights reserved.</p>
  </div>
</footer>
<!-- <script
  src='https://cdn.jotfor.ms/agent/embedjs/019898a18a5c7926ba088e0e2e81edd67f45/embed.js?skipWelcome=1&maximizable=1'>
</script> -->

<script>
function sendMessage() {
    const input = document.getElementById("userInput");
    const msg = input.value.trim();
    if (!msg) return;

    const chat = document.getElementById("messages");
    chat.innerHTML += `<div><b>You:</b> ${msg}</div>`;

    fetch("chatbot_api.php", {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify({message: msg})
    })
    .then(res => res.json())
    .then(data => {
        chat.innerHTML += `<div><b>Bot:</b> ${data.reply || data.error}</div>`;
        chat.scrollTop = chat.scrollHeight;
    });

    input.value = "";
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>