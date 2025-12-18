<?php
session_start();
include 'db_connect.php';

$result = $conn->query("SELECT * FROM packages");
$search = $_GET['search'] ?? '';
if (!empty($search)) {
    $stmt = $conn->prepare("SELECT * FROM packages WHERE status='active' AND title LIKE ?");
    $term = "%" . $search . "%";
    $stmt->bind_param("s", $term);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM packages WHERE status='active' ORDER BY id DESC");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Travel Adda</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    .card {
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
  transform: translateY(-6px);
  box-shadow: 0 12px 24px rgba(0, 0, 0, 0.25);
}

.card img {
  height: 200px;
  object-fit: cover;
  border-bottom: 1px solid #eee;
}

.card-body {
  padding: 16px;
  background: #fff;
}

.card-title {
  font-size: 1.2rem;
  font-weight: 700;
  margin-bottom: 8px;
  color: #222;
}

.card-text {
  font-size: 0.95rem;
  color: #555;
  margin-bottom: 12px;
}

.card .price {
  font-size: 1.1rem;
  font-weight: bold;
  color: #17B978;
  margin-bottom: 12px;
}

.card .btn {
  background-color: #17B978;
  border: none;
  color: #fff;
  font-weight: 600;
  border-radius: 8px;
  padding: 10px 14px;
  transition: background 0.3s ease;
}

.card .btn:hover {
  background-color: #129966;
}

     .blog-carousel-section h3 {
        font-size: 1.8rem;
        font-weight: bold;
        color: #17B978;
    }
    .blog-carousel-section p {
        font-size: 1rem;
        color: #555;
        margin-bottom: 15px;
    }
    .read-more {
        font-weight: bold;
        color: #17B978;
        text-decoration: none;
    }
    .read-more:hover {
        color: #129966;
    }
    .carousel-item img {
        height: 100%;
        object-fit: cover;
    }
    .discover-wonders-section {
  padding: 60px 0;
  background: #fffdf8;
  position: relative;
}

.discover-wonders-section .container {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
}

.discover-wonders-content {
  display: flex;
  justify-content: space-between;
  gap: 40px;
  flex-wrap: wrap;
}

.text-section {
  flex: 1 1 40%;
}

.text-section h1 {
  font-size: 38px;
  font-weight: bold;
  color: #333;
}

.text-section h1 span {
  color: #17B978; 
}

.text-section p {
  font-size: 16px;
  color: #555;
  margin: 15px 0 25px;
  line-height: 1.6;
}

.btn-discover {
  background-color: #17B978;
  color: white;
  padding: 10px 20px;
  text-decoration: none;
  border-radius: 30px;
  transition: 0.3s;
}

.btn-discover:hover {
  background-color: #149e65;
}

.image-gallery {
  display: flex;
  gap: 20px;
  flex: 1 1 50%;
  align-items: center;
  justify-content: center;
}

.main-img img {
  width: 220px;
  height: 300px;
  object-fit: cover;
  border-radius: 15px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.side-imgs {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.side-imgs img {
  width: 150px;
  height: 140px;
  object-fit: cover;
  border-radius: 15px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}
.image-row {
  display: flex;
  justify-content: center;
  gap: 25px;
  margin-top: 30px;
}

.image-card {
  position: relative;
  width: 150px;
  height: 150px;
  border-radius: 15px;
  overflow: hidden;
  box-shadow: 0px 4px 8px rgba(0,0,0,0.2);
}

.image-card img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.image-card:nth-child(1) {
  transform: rotate(-8deg);
}
.image-card:nth-child(2) {
  transform: rotate(-4deg);
}
.image-card:nth-child(3) {
  transform: rotate(4deg);
}
.image-card:nth-child(4) {
  transform: rotate(8deg);
}

.image-card:hover img {
  transform: scale(1.05);
}

.image-name {
  position: absolute;
  bottom: 10px;
  left: 0;
  width: 100%;
  text-align: center;
  color: white;
  font-weight: bold;
  background: rgba(0, 0, 0, 0.4);
  padding: 5px 0;
  font-size: 14px;
}
        .search-wrapper {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
            gap: 10px;
        }
        .search-bar {
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            width: 300px;
        }
        .btn-search {
            background-color: #17B978;
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 8px;
        }
        .btn-search:hover {
            background-color: #149f68;
        }
  </style>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="image/logo.png" alt="Travel Adda Logo" height="40" class="me-2">
            <span class="fw-bold text-white">Travel Adda</span>
        </a>
    <div class="navbar-nav ms-auto">
      <?php if (!isset($_SESSION['user']) && !isset($_SESSION['agent'])): ?>
        <a class="nav-item nav-link" href="customer_login.php">Login Now</a>
        <a class="nav-item nav-link" href="agent_login.php">Agent Login</a>
      <?php elseif (isset($_SESSION['user'])): ?>
        <a class="nav-item nav-link" href="logout.php">Logout</a>
      <?php elseif (isset($_SESSION['agent'])): ?>
        <a class="nav-item nav-link" href="logout.php">Logout</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<!-- Page Content -->
<div class="container mt-4">
  <h2 class="text-center mb-4">Welcome to Travel Adda</h2>

<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
  <ol class="carousel-indicators">
    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
  </ol>
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img class="d-block w-100" src="image/montain.jpg" alt="First slide">
      <div class="carousel-caption d-none d-md-block">
  <h2 class="fw-bold text-shadow">Discover Your Next Adventure</h2>
  <p class="lead text-shadow">Explore breathtaking destinations with Travel Adda.</p>
</div>

    </div>
    <div class="carousel-item">
      <img class="d-block w-100" src="image/stary.jpg" alt="Second slide">
      <div class="carousel-caption d-none d-md-block">
  <h2 class="fw-bold text-shadow">Discover Your Next Adventure</h2>
  <p class="lead text-shadow">Explore breathtaking destinations with Travel Adda.</p>
</div>
    </div>
    <div class="carousel-item">
      <img class="d-block w-100" src="image/camp.jpg" alt="Third slide">
      <div class="carousel-caption d-none d-md-block">
  <h2 class="fw-bold text-shadow">Discover Your Next Adventure</h2>
  <p class="lead text-shadow">Explore breathtaking destinations with Travel Adda.</p>
</div>
    </div>
  </div>
  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div><br>

<!-- <div class="container-fluid mt-4"> -->
    <h2 class="mb-4 text-center"><strong>Find Your Best</strong> Destination</h2>
    <h4 class="text-center">We have more than 100 destination you can choose</h4>

  <!-- 🔎 Search Section -->
  <form method="GET" class="search-wrapper">
      <input type="text" name="search" class="search-bar" placeholder="Search by title" value="<?= htmlspecialchars($search); ?>">
      <button type="submit" class="btn-search">Search</button>
  </form>

    <div class="image-row">
  <div class="image-card">
    <img src="image/montain.jpg" alt="Mountain">
    <div class="image-name">Mountain</div>
  </div>
  <div class="image-card">
    <img src="image/amazon.jpg" alt="Amazon">
    <div class="image-name">Amazon</div>
  </div>
  <div class="image-card">
    <img src="image/resort.jpg" alt="Beach Resort">
    <div class="image-name">Beach Resort</div>
  </div>
  <div class="image-card">
    <img src="image/hills.jpg" alt="Hills">
    <div class="image-name">Hills</div>
  </div>
</div><br><br>

  <div class="row justify-content-center">
    <?php while($row = $result->fetch_assoc()): ?>
      <div class="card" style="width: 18rem; margin: 10px;">
<div id="carousel<?= $row['id'] ?>" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <?php for ($i = 1; $i <= 4; $i++): ?>
      <?php if (!empty($row["image$i"])): ?>
        <div class="carousel-item <?= $i == 1 ? 'active' : '' ?>">
          <img src="assets/<?= htmlspecialchars($row["image$i"]); ?>" class="d-block w-100" height="200px" alt="Image <?= $i ?>">
        </div>
      <?php endif; ?>
    <?php endfor; ?>
  </div>
  <a class="carousel-control-prev" href="#carousel<?= $row['id'] ?>" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carousel<?= $row['id'] ?>" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
        <div class="card-body">
          <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
          <?php
            $desc = htmlspecialchars($row['description']);
            $short = mb_substr($desc, 0, 100);
            $isLong = mb_strlen($desc) > 100;
          ?>
          <p class="card-text">
            <span class="short-desc"><?= nl2br($short) ?><?= $isLong ? '...' : '' ?></span>
            <?php if ($isLong): ?>
              <span class="full-desc d-none"><?= nl2br($desc) ?></span>
              <a href="javascript:void(0);" class="toggle-desc text-primary" onclick="toggleDesc(this)">Show more</a>
            <?php endif; ?>
          </p>
          <p><strong><?php echo htmlspecialchars($row['duration']); ?> | ₹<?php echo htmlspecialchars($row['price']); ?></strong></p>
          <?php if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'customer'): ?>
            <a href="customer_login.php" class="btn btn-primary">Login to Book</a>
          <?php else: ?>
            <a href="book.php?package_id=<?php echo $row['id']; ?>" class="btn btn-success">Book Now</a>
          <?php endif; ?>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
          </div>

        <!-- 📝 Blog Section -->
  <section class="blog-carousel-section py-5">
    <div class="container">
      <div class="section-title text-center mb-4">
        <h2><span>Our</span> Blog</h2>
        <p>An insight into the incredible experiences around the world</p>
      </div>

      <div id="singleBlogCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">

          <div class="carousel-item active">
            <div class="row g-0 align-items-center flex-md-row flex-column">
              <div class="col-md-4">
                <img src="image/kashmir.png" class="img-fluid rounded-start" alt="Hiking">
              </div>
              <div class="col-md-6 p-4">
                <h3>Hiking in Scenic Hills</h3>
                <p>Breathe in fresh mountain air and enjoy breathtaking views while hiking through serene hills.</p>
                <a href="#" class="read-more">Read more →</a>
              </div>
            </div>
          </div>

          <div class="carousel-item">
            <div class="row g-0 align-items-center flex-md-row flex-column">
              <div class="col-md-4">
                <img src="image/nightlife.png" class="img-fluid rounded-start" alt="Nightlife">
              </div>
              <div class="col-md-6 p-4">
                <h3>Nightlife in the City</h3>
                <p>Experience vibrant nightlife filled with music, lights, and excitement.</p>
                <a href="#" class="read-more">Read more →</a>
              </div>
            </div>
          </div>

          <div class="carousel-item">
            <div class="row g-0 align-items-center flex-md-row flex-column">
              <div class="col-md-4">
                <img src="image/tropical.png" class="img-fluid rounded-start" alt="Tropical Paradise">
              </div>
              <div class="col-md-6 p-4">
                <h3>Tropical Paradise Getaways</h3>
                <p>Unwind under palm trees and soak up the sun in stunning tropical destinations.</p>
                <a href="#" class="read-more">Read more →</a>
              </div>
            </div>
          </div>

        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#singleBlogCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#singleBlogCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon"></span>
        </button>
      </div>
    </div>
  </section>

  <!-- Feedback Section -->
<h3 class="text-center mt-5">What Customers Are Saying</h3><br>

<div class="row align-items-center" style="min-height: 250px;">
    
    <!-- Left Image -->
    <div class="col-md-4 text-center">
        <img src="image/feedback.png" alt="Happy Customers" 
             class="img-fluid rounded" style="max-height: 250px;">
    </div>

    <!-- Right Feedbacks (AJAX Injected) -->
    <div class="col-md-4">
        <div class="customer-feedbacks" id="feedback-section">
            <!-- AJAX will inject feedback here -->
        </div>
    </div>

</div>

<script>
function loadFeedbacks(page = 1) {
    fetch('load_feedbacks.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `page=${page}`
    })
    .then(response => response.text())
    .then(html => {
        document.getElementById('feedback-section').innerHTML = html;
    });
}

// Initial load
loadFeedbacks();

// Delegate click for pagination buttons
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('fb-page-link')) {
        e.preventDefault();
        const page = e.target.getAttribute('data-page');
        loadFeedbacks(page);
    }
});

// Toggle comment (same function as before)
function toggleComment(link) {
    const commentBox = link.closest('.customer-comment');
    const shortText = commentBox.querySelector('.short-comment');
    const fullText = commentBox.querySelector('.full-comment');

    if (fullText.classList.contains('d-none')) {
        shortText.style.display = 'none';
        fullText.classList.remove('d-none');
        link.textContent = 'Show less';
    } else {
        shortText.style.display = 'inline';
        fullText.classList.add('d-none');
        link.textContent = 'Show more';
    }
}
</script>
<script>
function toggleDesc(link) {
    const cardText = link.closest('.card-text');
    const shortDesc = cardText.querySelector('.short-desc');
    const fullDesc = cardText.querySelector('.full-desc');
    if (fullDesc.classList.contains('d-none')) {
        shortDesc.style.display = 'none';
        fullDesc.classList.remove('d-none');
        link.textContent = 'Show less';
    } else {
        shortDesc.style.display = 'inline';
        fullDesc.classList.add('d-none');
        link.textContent = 'Show more';
    }
}
function toggleComment(link) {
    const commentBox = link.closest('.comment');
    const shortText = commentBox.querySelector('.short-comment');
    const fullText = commentBox.querySelector('.full-comment');

    if (fullText.classList.contains('d-none')) {
        shortText.style.display = 'none';
        fullText.classList.remove('d-none');
        link.textContent = 'Show less';
    } else {
        shortText.style.display = 'inline';
        fullText.classList.add('d-none');
        link.textContent = 'Show more';
    }
}
</script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>
<?php include "footer.php"; ?>
