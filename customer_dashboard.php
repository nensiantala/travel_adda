<?php
session_start();
if (!isset($_SESSION['customer_id'])) {
    header("Location: customer_login.php");
    exit();
}
include 'db_connect.php';
include 'header.php';

$search = $_GET['search'] ?? '';
if (!empty($search)) {
    $stmt = $conn->prepare("SELECT * FROM packages WHERE status='active' AND title LIKE ?");
    $term = "%" . $search . "%";
    $stmt->bind_param("s", $term);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("
        SELECT p.*, 
            (SELECT ROUND(AVG(rating), 1) FROM feedback WHERE package_id = p.id) AS avg_rating 
        FROM packages p
        WHERE p.status = 'active'
        ORDER BY p.id DESC
    ");
}

// category filter only
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

// If category selected -> prepared stmt case-insensitive
if ($category !== '') {
    $sql = "
      SELECT p.*, 
        (SELECT ROUND(AVG(rating), 1) FROM feedback WHERE package_id = p.id) AS avg_rating
      FROM packages p
      WHERE p.status = 'active'
        AND LOWER(p.category) = ?
      ORDER BY p.id DESC
    ";
    $stmt = $conn->prepare($sql);
    $lowerCat = mb_strtolower($category, 'UTF-8');
    $stmt->bind_param('s', $lowerCat);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // default: show all active packages
    $sql = "
      SELECT p.*, 
        (SELECT ROUND(AVG(rating), 1) FROM feedback WHERE package_id = p.id) AS avg_rating
      FROM packages p
      WHERE p.status = 'active'
      ORDER BY p.id DESC
    ";
    $result = $conn->query($sql);
}
?>

<?php if (isset($_GET['booked'])): ?>
    <div class="alert alert-success text-center">Booking successful!</div>
<?php elseif (isset($_GET['error']) && $_GET['error'] == 'invalid_package'): ?>
    <div class="alert alert-danger text-center">Invalid or inactive package.</div>
<?php elseif (isset($_GET['error']) && $_GET['error'] == 'booking_failed'): ?>
    <div class="alert alert-danger text-center">Booking failed. Please try again.</div>
<?php endif; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Customer Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/style.css">
    <style>
        /* Decorative Background Circles */
        body {
            position: relative;
            overflow-x: hidden;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: -1;
        }
        
       /* Decorative Circles and Dots */
.bg-circle, .bg-dot {
    position: fixed;
    border-radius: 50%;
    opacity: 0.3;
    pointer-events: none;
    z-index: -1;
    animation: float 8s ease-in-out infinite;
}

/* Circles */
.bg-circle:nth-of-type(1) {
    width: 300px;
    height: 300px;
    background: linear-gradient(45deg, #17B978, #14a76c);
    top: 10%;
    left: -150px;
    animation-delay: 0s;
}

.bg-circle:nth-of-type(2) {
    width: 200px;
    height: 200px;
    background: linear-gradient(45deg, #00435a, #17B978);
    top: 60%;
    right: -100px;
    animation-delay: 2s;
}

.bg-circle:nth-of-type(3) {
    width: 150px;
    height: 150px;
    background: linear-gradient(45deg, #ffc107, #ff9800);
    top: 20%;
    right: 20%;
    animation-delay: 4s;
}

.bg-circle:nth-of-type(4) {
    width: 100px;
    height: 100px;
    background: linear-gradient(45deg, #e91e63, #f44336);
    bottom: 20%;
    left: 10%;
    animation-delay: 1s;
}

.bg-circle:nth-of-type(5) {
    width: 80px;
    height: 80px;
    background: linear-gradient(45deg, #9c27b0, #673ab7);
    top: 80%;
    left: 60%;
    animation-delay: 3s;
}

.bg-circle:nth-of-type(6) {
    width: 120px;
    height: 120px;
    background: linear-gradient(45deg, #00bcd4, #009688);
    top: 40%;
    left: 80%;
    animation-delay: 5s;
}

/* Dots */
.bg-dot:nth-of-type(7) {
    width: 8px;
    height: 8px;
    background: #17B978;
    top: 15%;
    left: 30%;
    animation-delay: 0.5s;
}
.bg-dot:nth-of-type(8) {
    width: 8px;
    height: 8px;
    background: #00435a;
    top: 25%;
    right: 25%;
    animation-delay: 1.5s;
}
.bg-dot:nth-of-type(9) {
    width: 8px;
    height: 8px;
    background: #ffc107;
    top: 70%;
    left: 20%;
    animation-delay: 2.5s;
}
.bg-dot:nth-of-type(10) {
    width: 8px;
    height: 8px;
    background: #e91e63;
    top: 85%;
    right: 15%;
    animation-delay: 3.5s;
}
.bg-dot:nth-of-type(11) {
    width: 8px;
    height: 8px;
    background: #9c27b0;
    top: 50%;
    left: 15%;
    animation-delay: 4.5s;
}
.bg-dot:nth-of-type(12) {
    width: 8px;
    height: 8px;
    background: #00bcd4;
    top: 35%;
    right: 40%;
    animation-delay: 5.5s;
}

/* Floating Animation */
@keyframes float {
    0%, 100% {
        transform: translateY(0px) rotate(0deg);
    }
    50% {
        transform: translateY(-20px) rotate(180deg);
    }
}

        
        /* Ensure content is above background elements */
        .container-fluid {
            position: relative;
            z-index: 1;
        }
        
        .card-container {
            transition: transform 0.3s;
        }
        /* .card-container:hover {
            transform: scale(1.02);
        } */
        .blurred {
            opacity: 0.5;
            pointer-events: none;
        }
        .inactive-badge {
            display: inline-block;
            padding: 5px 10px;
            font-size: 0.8rem;
            background-color: #6c757d;
            color: #fff;
            border-radius: 5px;
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
        .card .card-title {
            font-weight: bold;
            color: #00435a;
        }

        /* .desc-text { white-space: pre-line; } */
        .desc-wrapper.expanded .short-desc { display: none; }
        .desc-wrapper.expanded .full-desc { display: block; }
/* .desc-wrapper {
  margin: 0.10rem 0;
} */

.short-desc,
.full-desc {
  font-size: 0.95rem;
  color: #444;
  /* margin: 0; */
  line-height: 1.5;
}

.full-desc {
  display: none;
}

.show-more-link {
  color: #17B978;
  cursor: pointer;
  font-weight: 500;
  /* margin-top: 4px; */
  display: inline-block;
  font-size: 0.9rem;
}

        .customer-feedbacks .feedback-card {
            background-color: #f8f9fa;
            border-left: 5px solid #17B978;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            height: 100%;
        }
        .customer-feedbacks .stars {
            color: #ffc107;
            font-size: 1.2rem;
        }
        .customer-feedbacks .package-name {
            font-weight: bold;
            color: #00695c;
        }
        .customer-feedbacks .customer-comment {
            margin: 10px 0;
            word-wrap: break-word;
        }
        .customer-feedbacks .toggle-comment {
            cursor: pointer;
            font-weight: 500;
            display: inline-block;
            margin-top: 5px;
        }
        .customer-feedbacks .date {
            font-size: 0.8rem;
            color: #555;
        }
        .card {
  width: 100%;
  max-width: 380px; /* Wider than before */
  min-height: 380px; /* Ensures equal height */
  margin: 10px auto;
  padding: 10px;
  border-radius: 16px;
  box-shadow: 0 10px 20px rgba(0,0,0,0.08);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  background-color: #fff;
  /* display: flex;
  flex-direction: column;
  justify-content: space-between; */
}

/* 
.card:hover {
  transform: scale(0.98);
  box-shadow: 0 15px 25px rgba(0,0,0,0.1);
} */

.card h5, .card p {
  margin-bottom: 0.75rem;
}

.card .btn {
  width: 100%;
  padding: 10px 0;
  font-weight: bold;
  font-size: 16px;
}

.card-container {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  padding: 20px 15px;
}
.rating-badge {
    background: rgba(0, 0, 0, 0.6);
    font-weight: 500;
    font-size: 0.85rem;
    backdrop-filter: blur(3px);
    border-radius: 8px;
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

    </style>
</head>
<body>

<!-- Decorative Background Circles -->
<div class="bg-circle"></div>
<div class="bg-circle"></div>
<div class="bg-circle"></div>
<div class="bg-circle"></div>
<div class="bg-circle"></div>
<div class="bg-circle"></div>
<div class="bg-dot"></div>
<div class="bg-dot"></div>
<div class="bg-dot"></div>
<div class="bg-dot"></div>
<div class="bg-dot"></div>
<div class="bg-dot"></div>

<!-- Discover Wonders Section -->
<section class="discover-wonders-section">
  <div class="container">
    <div class="discover-wonders-content">
      <div class="text-section ms-5">
        <h1>Discover the World's <span>Hidden</span> Wonders</h1>
        <p>
          Embark on unique journeys and hidden gems that ignite unmatched excitement. From serene escapes to
          remarkable destinations, see the best the world has to offer and live landscapes beyond imagination.
        </p>
        <a href="packages.php" class="btn-discover">Start Exploring</a>
      </div>
      <div class="image-gallery ms-2">
        <div class="main-img"><img src="image/discover1.jpg" alt="Main Place"></div>
        <div class="side-imgs">
          <img src="image/discover2.jpg" alt="Place 2">
          <img src="image/discover3.jpg" alt="Place 3">
        </div>
      </div>
    </div>
  </div>
</section>

<div class="container-fluid mt-4">
    <h2 class="mb-4 text-center"><strong>Find Your Best</strong> Destination</h2>
    <h4 class="text-center">We have more than 100 destination you can choose</h4>

    <form method="GET" class="search-wrapper">
        <input type="text" name="search" class="search-bar" placeholder="Search by title" value="<?= htmlspecialchars($search); ?>">
        <button type="submit" class="btn-search">Search</button>
    </form>
<!-- Category Filter -->
<!-- Image-based Category Selector -->
<div class="category-grid text-center" id="categoryGrid">
  <!-- data-cat value should be exact category used in DB (case-insensitive) -->
  <button type="button" class="category-card" data-cat="">
    <img src="image/all.jpg" alt="All">
    <span class="category-name">All</span>
  </button>

  <button type="button" class="category-card" data-cat="Beach">
    <img src="image/resort.jpg" alt="Beach">
    <span class="category-name">Beach</span>
  </button>

  <button type="button" class="category-card" data-cat="Mountain">
    <img src="image/montain.jpg" alt="Mountain">
    <span class="category-name">Mountain</span>
  </button>

  <button type="button" class="category-card" data-cat="City Vibe">
    <img src="image/city.jpg" alt="City Vibe">
    <span class="category-name">City Vibe</span>
  </button>

  <button type="button" class="category-card" data-cat="Nature">
    <img src="image/amazon.jpg" alt="Nature">
    <span class="category-name">Nature</span>
  </button>

  <button type="button" class="category-card" data-cat="Heritage">
    <img src="image/heritage.jpg" alt="Heritage">
    <span class="category-name">Heritage</span>
  </button>

  <button type="button" class="category-card" data-cat="Adventure">
    <img src="image/advanture.jpg" alt="Adventure">
    <span class="category-name">Adventure</span>
  </button>
</div>

<style>
.category-row {
  display: flex;
  justify-content: center;
  gap: 25px;
  margin: 40px 0;
}

.category-card {
  position: relative;
  width: 150px;
  height: 150px;
  border-radius: 15px;
  overflow: hidden;
  cursor: pointer;
  box-shadow: 0px 4px 8px rgba(0,0,0,0.2);
  transition: transform 0.3s ease;
  margin-left: 20px;
}

.category-card img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

/* Rotate each category slightly for style */
.category-card:nth-child(1) { transform: rotate(-8deg); }
.category-card:nth-child(2) { transform: rotate(-4deg); }
.category-card:nth-child(3) { transform: rotate(4deg); }
.category-card:nth-child(4) { transform: rotate(-6deg); }
.category-card:nth-child(5) { transform: rotate(6deg); }
.category-card:nth-child(6) { transform: rotate(-8deg); }
.category-card:nth-child(7) { transform: rotate(4deg); }


/* Hover effect */
.category-card:hover {
  transform: scale(1.08);
  z-index: 5;
}
.category-card:hover img {
  transform: scale(1.1);
}

.category-name {
  position: absolute;
  bottom: 10px;
  left: 0;
  width: 100%;
  text-align: center;
  color: #fff;
  font-weight: bold;
  background: rgba(0, 0, 0, 0.4);
  padding: 5px 0;
  font-size: 14px;
}

</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const cards = document.querySelectorAll('.category-card');
  const url = new URL(window.location.href);
  const currentCat = (url.searchParams.get('category') || '').trim();

  // Mark active card (so UI highlights the selected one)
  cards.forEach(c => {
    if ((c.dataset.cat || '') === currentCat) c.classList.add('active');
  });

  // Remove the category param from the URL without reloading the page
  // -> This makes the next refresh load "all packages".
  if (currentCat !== '') {
    url.searchParams.delete('category');
    // Build new URL keeping other params (if any)
    const newQuery = url.searchParams.toString();
    const newUrl = url.pathname + (newQuery ? '?' + newQuery : '');
    history.replaceState(null, '', newUrl);
  }

  // Click handlers for category cards (navigate to filtered url)
  cards.forEach(card => {
    card.addEventListener('click', () => {
      const cat = (card.dataset.cat || '').trim();
      if (!cat) {
        // show ALL — go to page without query string
        window.location.href = window.location.pathname;
      } else {
        const newUrl = new URL(window.location.href);
        newUrl.searchParams.set('category', cat);
        // remove other params you don't want to keep, e.g. search:
        // newUrl.searchParams.delete('search');
        window.location.href = newUrl.toString();
      }
    });
  });
});
</script>

<br><br>
    <h2 class="mb-4 text-center">Explore the packages</h2>

<style>
/* Outer custom carousel */
.outer-carousel {
  position: relative;
  overflow: hidden;
  width: 100%;
}
.outer-carousel-track {
  display: flex;
  transition: transform 0.5s ease;
}
.outer-carousel-slide {
  display: flex;
  justify-content: center;
  gap: 1rem;
  min-width: 100%;
  padding: 0 15px;
}
.outer-carousel-btn {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background: rgba(134, 180, 162, 0.9);
  border: none;
  color: white;
  font-size: 1rem;
  padding: 0.4rem 0.8rem;
  border-radius: 50%;
  cursor: pointer;
  z-index: 10;
}
.outer-carousel-btn.left { left: 10px; }
.outer-carousel-btn.right { right: 10px; }
</style>

<div class="outer-carousel">
  <button class="outer-carousel-btn left" onclick="moveOuter(-1)">&#10094;</button>
  <div class="outer-carousel-track" id="outerTrack">

    <?php if ($result && $result->num_rows > 0): ?>
      <?php 
      $packages = $result->fetch_all(MYSQLI_ASSOC);
      $chunks = array_chunk($packages, 2); // 2 cards per slide
      foreach ($chunks as $group): ?>
        <div class="outer-carousel-slide">
          <?php foreach ($group as $row): ?>
            <div class="col-md-6 col-lg-5 d-flex">
              <div class="card-container flex-fill">
                <div class="card position-relative <?= ($row['status'] != 'active') ? 'blurred' : '' ?>">

                  <!-- Inner image carousel -->
                  <?php 
                    $innerId = "innerCarousel" . $row['id']; 
                    $hasImages = false;
                  ?>
                  <div id="<?= $innerId ?>" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                      <?php 
                      for ($j = 1; $j <= 4; $j++): 
                        if (!empty($row["image$j"])): 
                          $hasImages = true; ?>
                          <div class="carousel-item <?= $j == 1 ? 'active' : '' ?>">
                            <img src="assets/<?= htmlspecialchars($row["image$j"]); ?>" 
                                 class="d-block w-100 rounded-top" height="200" 
                                 alt="Image <?= $j ?>">
                          </div>
                        <?php endif; 
                      endfor; ?>
                    </div>
                    <?php if ($hasImages): ?>
                      <button class="carousel-control-prev" type="button" data-bs-target="#<?= $innerId ?>" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                      </button>
                      <button class="carousel-control-next" type="button" data-bs-target="#<?= $innerId ?>" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                      </button>
                    <?php endif; ?>
                  </div>

                  <!-- Rating badge -->
                  <div class="rating-badge position-absolute top-0 end-0 m-2 px-2 py-1 rounded d-flex align-items-center">
                    <span class="me-1" style="color: #ffc107;">&#9733;</span>
                    <span class="text-white small">
                      <?= isset($row['avg_rating']) && $row['avg_rating'] !== null && $row['avg_rating'] !== ''
                          ? htmlspecialchars($row['avg_rating']) . ' / 5'
                          : 'No ratings' ?>
                    </span>
                  </div>

                  <!-- Card Body -->
                  <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($row['title']); ?></h5>

                    <?php
                      $desc = htmlspecialchars($row['description']);
                      $short = substr($desc, 0, 100);
                      $isLong = strlen($desc) > 100;
                    ?>
                    <div class="desc-wrapper desc-text">
                      <div class="short-desc"><?= nl2br($short) ?><?= $isLong ? '...' : '' ?></div>
                      <div class="full-desc"><?= nl2br($desc) ?></div>
                      <?php if ($isLong): ?>
                        <div class="show-more-link" onclick="toggleDescription(this)">Show more</div>
                      <?php endif; ?>
                    </div>

                    <p><strong>Trip Dates:</strong> <?= htmlspecialchars($row['start_date']) ?> to <?= htmlspecialchars($row['end_date']) ?></p>
                    <p><strong>Duration:</strong> <?= htmlspecialchars($row['duration']) ?></p>
                    <p><strong>Price:</strong> ₹<?= number_format($row['price'], 2) ?></p>
                    <p><strong>Category:</strong> <?php echo $row['category']; ?></p>

                    <?php if ($row['status'] == 'active'): ?>
                      <a href="book.php?package_id=<?= $row['id']; ?>" class="btn btn-success w-100 mb-2">Book Now</a>
                      <a href="package_details.php?id=<?= $row['id']; ?>" class="btn btn-primary w-100">View Details</a>
                    <?php else: ?>
                      <span class="inactive-badge">Inactive</span>
                      <button class="btn btn-secondary w-100 mt-2" disabled>Not Available</button>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endforeach; ?>

    <?php else: ?>
      <div class="outer-carousel-slide text-center p-5">
        <p class="text-danger">No packages found!</p>
      </div>
    <?php endif; ?>

  </div>
  <button class="outer-carousel-btn right" onclick="moveOuter(1)">&#10095;</button>
</div>

<script>
let outerIndex = 0;
const outerTrack = document.getElementById('outerTrack');
const outerSlides = document.querySelectorAll('.outer-carousel-slide');
const totalSlides = outerSlides.length;

function moveOuter(dir) {
  outerIndex += dir;
  if (outerIndex < 0) outerIndex = totalSlides - 1;
  if (outerIndex >= totalSlides) outerIndex = 0;
  outerTrack.style.transform = `translateX(-${outerIndex * 100}%)`;
}
</script>

<div class="text-center mt-3">
    <a href="packages.php" class="btn btn-secondary">See All Packages</a>
</div>

<section class="blog-carousel-section py-5">
    <div class="container">
        <div class="section-title text-center mb-4">
            <h2><span>Our</span> Blog</h2>
            <p>An insight into the incredible experiences around the world</p>
        </div>

        <div id="singleBlogCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">

                <!-- Slide 1 -->
                <div class="carousel-item active">
                    <div class="row g-0 align-items-center flex-md-row flex-column">
                        <div class="col-md-4">
                            <img src="image/kashmir.png" class="img-fluid w-30 rounded-start" alt="Hiking in Scenic Hills">
                        </div>
                        <div class="col-md-6 p-4">
                            <h3>Hiking in Scenic Hills</h3>
                            <p>Breathe in fresh mountain air and take in breathtaking views while hiking through serene, picturesque hills.</p>
                            <a href="#" class="read-more">Read more →</a>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="carousel-item">
                    <div class="row g-0 align-items-center flex-md-row flex-column">
                        <div class="col-md-4">
                            <img src="image/nightlife.png" class="img-fluid w-100 rounded-start" alt="Nightlife in the City">
                        </div>
                        <div class="col-md-6 p-4">
                            <h3>Nightlife in the City</h3>
                            <p>Experience vibrant nightlife scenes filled with music, lights, and excitement in the most lively cities.</p>
                            <a href="#" class="read-more">Read more →</a>
                        </div>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="carousel-item">
                    <div class="row g-0 align-items-center flex-md-row flex-column">
                        <div class="col-md-4">
                            <img src="image/tropical.png" class="img-fluid w-100 rounded-start" alt="Tropical Paradise Getaways">
                        </div>
                        <div class="col-md-6 p-4">
                            <h3>Tropical Paradise Getaways</h3>
                            <p>Unwind under palm trees and soak up the sun in some of the world’s most stunning tropical destinations.</p>
                            <a href="#" class="read-more">Read more →</a>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Controls -->
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



</div>
<script>
function toggleDescription(el) {
    const wrapper = el.closest('.desc-wrapper');
    const shortDesc = wrapper.querySelector('.short-desc');
    const fullDesc = wrapper.querySelector('.full-desc');

    if (fullDesc.style.display === 'block') {
        fullDesc.style.display = 'none';
        shortDesc.style.display = 'block';
        el.textContent = 'Show more';
    } else {
        fullDesc.style.display = 'block';
        shortDesc.style.display = 'none';
        el.textContent = 'Show less';
    }
}
</script>

<script>

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include "footer.php"; ?>
