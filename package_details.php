<?php
session_start();
if (!isset($_SESSION['customer_id'])) {
    header("Location: customer_login.php");
    exit();
}

include 'db_connect.php';

if (!isset($_GET['id'])) {
    header("Location: packages.php");
    exit();
}

$package_id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT * FROM packages WHERE id = ? AND status = 'active'");
$stmt->bind_param("i", $package_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<div class='container mt-5 text-center'><h4 class='text-danger'>Package not found or is not available.</h4></div>";
    include 'footer.php';
    exit();
}

$package = $result->fetch_assoc();

// Set page title
$page_title = htmlspecialchars($package['title']) . ' - Travel Adda';

include 'header.php';

// Average rating
$avgStmt = $conn->prepare("SELECT AVG(rating) as avg_rating FROM feedback WHERE package_id = ?");
$avgStmt->bind_param("i", $package_id);
$avgStmt->execute();
$avgResult = $avgStmt->get_result();
$avgRating = round($avgResult->fetch_assoc()['avg_rating'], 1);

$limit = 5;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $limit;



if (!isset($_GET['id'])) {
    die("Invalid Request!");
}
$package_id = $_GET['id'];
$customer_id = $_SESSION['customer_id']; // assuming you store customer id in session

// fetch package info
$sql = "SELECT * FROM packages WHERE id='$package_id'";
$result = mysqli_query($conn, $sql);
$package = mysqli_fetch_assoc($result);

// fetch booking info (if any)
$booking_sql = "SELECT payment_status FROM bookings 
                WHERE package_id='$package_id' AND customer_id='$customer_id' 
                ORDER BY id DESC LIMIT 1";
$booking_result = mysqli_query($conn, $booking_sql);
$booking = mysqli_fetch_assoc($booking_result);


if (!isset($_GET['id'])) {
    die("Invalid Request!");
}
$package_id = $_GET['id'];

// fetch package details
$sql = "SELECT * FROM packages WHERE id='$package_id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
} else {
    die("Package not found!");
}

?>

<style>
    body {
        font-family: 'Nunito Sans', sans-serif;
        background-color: #f7f7f7;
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
    .details-box {
        background: #fff;
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.08);
        height: 100%;
    }
    .carousel-inner img {
        width: 100%;
        height: 100%;
        max-height: 550px;
        object-fit: cover;
        border-radius: 10px;
    }
    .btn-success {
        background-color: #17B978;
        border: none;
    }
    .btn-success:hover {
        background-color: #14a76c;
    }
    .itinerary-tab {
        background: #fff;
        border-radius: 10px;
        padding: 5px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        margin-bottom: 5px;
    }

    .day-title {
        font-weight: bold;
        font-size: 1rem;
        color: #00435a;
    }
    .desc-box {
        max-height: 120px;
        overflow: hidden;
        position: relative;
    }
    .desc-box.expanded {
        max-height: none;
    }
    .show-more {
        color: #17B978;
        cursor: pointer;
        font-weight: 500;
        display: inline-block;
        margin-top: 4px;
    }
    .star {
        font-size: 2rem;
        color: #ccc;
        cursor: pointer;
    }
    .star.selected {
        color: gold;
    }
    .comment-wrapper {
        position: relative;
    }
    .full-comment {
        display: none;
    }
    .toggle-comment {
        color: #17B978;
        font-weight: 500;
        cursor: pointer;
    }
</style>

<div class="container mt-5">
    <h2 class="mb-4 text-center"><?= htmlspecialchars($package['title']) ?></h2>

    <div class="row g-4 align-items-start">
        <!-- Left: Image Carousel -->
        <div class="col-md-7">
            <div id="carousel<?= $package['id'] ?>" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php
                    $hasImage = false;
                    for ($i = 1; $i <= 4; $i++) {
                        if (!empty($package["image$i"])) {
                            $hasImage = true;
                            echo '<div class="carousel-item ' . ($i == 1 ? 'active' : '') . '">
                                    <img src="assets/' . htmlspecialchars($package["image$i"]) . '" class="d-block w-100" alt="Image">
                                  </div>';
                        }
                    }
                    if (!$hasImage) {
                        echo '<div class="carousel-item active">
                                <img src="assets/default.jpg" class="d-block w-100" alt="Default Image">
                              </div>';
                    }
                    ?>
                </div>
                <?php if ($hasImage): ?>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carousel<?= $package['id'] ?>" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carousel<?= $package['id'] ?>" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                <?php endif; ?>
            </div>
            <a href="book.php?package_id=<?= $package['id'] ?>" class="btn btn-success btn-lg mt-4 w-100">Book Now</a>
                            <a href="payment.php?booking_id=<?php echo $row['id']; ?>" class="btn btn-success btn-lg mt-2 w-100">
   Proceed to Payment
</a>   
            <a href="customer_dashboard.php" class="btn btn-secondary mt-2 w-100">Back to Dashboard</a>
            <a href="packages.php" class="btn btn-secondary mt-2 w-100">See All Packages</a>
            </div>

        <!-- Right: Package Details -->
        <div class="col-md-5">
            <div class="details-box">
                <h4>Description</h4>
                <div class="desc-box" id="descBox"><?= nl2br(htmlspecialchars($package['description'])) ?></div>
                <?php if (strlen($package['description']) > 300): ?>
                    <span class="show-more" onclick="toggleDescription()">Show more</span>
                <?php endif; ?>

                <h5 class="mt-3">Duration: <?= htmlspecialchars($package['duration']) ?></h5>
                <h5>Price: ₹<?= htmlspecialchars($package['price']) ?></h5>
                <h5>Trip Dates: <?= date("d M Y", strtotime($package['start_date'])) ?> to <?= date("d M Y", strtotime($package['end_date'])) ?></h5>

                <h4 class="mt-4">Itinerary</h4>
                <?php
                if (!empty($package['itinerary'])) {
                    $days = explode("\n", $package['itinerary']);
                    foreach ($days as $day) {
                        $parts = explode("|", trim($day));
                        if (count($parts) == 3) {
                            echo '<div class="itinerary-tab">
                                    <div class="day-title">' . htmlspecialchars($parts[0]) . ' — ' . htmlspecialchars($parts[1]) . '</div>
                                    <div class="text-muted">Duration: ' . htmlspecialchars($parts[2]) . '</div>
                                  </div>';
                        }
                    }
                } else {
                    echo "<p class='text-muted'>Itinerary not available.</p>";
                }
                ?>
                <?php if ($booking) { ?>
   <p><strong>Payment:</strong> <?php echo $booking['payment_status']; ?></p>
<?php } else { ?>
   <p><em>You have not booked this package yet.</em></p>
<?php } ?>  
               <h5 class="mt-3 text-muted">City: <?= htmlspecialchars($package['location']) ?></h5>
                <div class="weather-info" id="weather-<?= $package['id']; ?>">Loading weather...</div>
            </div>
        </div>
    </div>

    <!-- Feedback Section -->
     <div class="row m-4 p-4">
    <hr class="mt-5">
    <h4 class="text-center mb-3">Customer Feedback</h4>
    <p class="text-center"><strong>Average Rating:</strong> <?= $avgRating ? "$avgRating / 5" : "No ratings yet" ?></p>

    <form id="feedbackForm" class="mb-4">
        <input type="hidden" name="package_id" value="<?= $package_id ?>">
        <div class="mb-2 text-center">
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <span class="star" data-value="<?= $i ?>">&#9734;</span>
            <?php endfor; ?>
            <input type="hidden" name="rating" id="ratingInput" value="0" required>
        </div>
        <div class="mb-3">
            <textarea name="comment" class="form-control" placeholder="Write your comment..." required></textarea>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary">Submit Feedback</button>
        </div>
    </form>

    <div id="feedbackDisplay">
        <?php
        $feedStmt = $conn->prepare("SELECT f.rating, f.comment, c.username, f.created_at 
            FROM feedback f 
            JOIN customers c ON f.customer_id = c.id 
            WHERE f.package_id = ? AND f.status = 'approved' 
            ORDER BY f.created_at DESC 
            LIMIT ? OFFSET ?");
        $feedStmt->bind_param("iii", $package_id, $limit, $offset);
        $feedStmt->execute();
        $feedbacks = $feedStmt->get_result();

        while ($fb = $feedbacks->fetch_assoc()):
            $comment = htmlspecialchars($fb['comment']);
            $short = substr($comment, 0, 200);
            $isLong = strlen($comment) > 200;
        ?>
        <div class="border rounded p-3 mb-3 bg-light">
            <strong><?= htmlspecialchars($fb['username']) ?></strong> rated: <?= $fb['rating'] ?>/5<br>
            <small class="text-muted"><?= $fb['created_at'] ?></small>
            <div class="comment-wrapper">
                <div class="short-comment"><?= nl2br($short) ?><?= $isLong ? '...' : '' ?></div>
                <?php if ($isLong): ?>
                    <div class="full-comment"><?= nl2br($comment) ?></div>
                    <div class="toggle-comment" onclick="toggleComment(this)">Show more</div>
                <?php endif; ?>
            </div>
        </div>
        <?php endwhile; ?>
    </div>

    <!-- Pagination -->
    <?php
    $countStmt = $conn->prepare("SELECT COUNT(*) as total FROM feedback WHERE package_id = ? AND status = 'approved'");
    $countStmt->bind_param("i", $package_id);
    $countStmt->execute();
    $totalRows = $countStmt->get_result()->fetch_assoc()['total'];
    $totalPages = ceil($totalRows / $limit);
    ?>
    <nav>
        <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
                <li class="page-item"><a class="page-link" href="?id=<?= $package_id ?>&page=<?= $page - 1 ?>">Previous</a></li>
            <?php endif; ?>
            <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                <li class="page-item <?= $p == $page ? 'active' : '' ?>"><a class="page-link" href="?id=<?= $package_id ?>&page=<?= $p ?>"><?= $p ?></a></li>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?>
                <li class="page-item"><a class="page-link" href="?id=<?= $package_id ?>&page=<?= $page + 1 ?>">Next</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</div>
            </div>

<script>
function toggleDescription() {
    const box = document.getElementById("descBox");
    box.classList.toggle("expanded");
    document.querySelector(".show-more").textContent =
        box.classList.contains("expanded") ? "Show less" : "Show more";
}
function toggleComment(link) {
    const wrapper = link.closest('.comment-wrapper');
    const full = wrapper.querySelector('.full-comment');
    const short = wrapper.querySelector('.short-comment');
    if (full.style.display === 'none' || full.style.display === '') {
        short.style.display = 'none';
        full.style.display = 'block';
        link.textContent = 'Show less';
    } else {
        short.style.display = 'block';
        full.style.display = 'none';
        link.textContent = 'Show more';
    }
}
document.querySelectorAll('.star').forEach(star => {
    star.addEventListener('click', () => {
        const value = star.getAttribute('data-value');
        document.getElementById('ratingInput').value = value;
        document.querySelectorAll('.star').forEach(s => s.classList.remove('selected'));
        for (let i = 0; i < value; i++) {
            document.querySelectorAll('.star')[i].classList.add('selected');
        }
    });
});
document.getElementById('feedbackForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const formData = new FormData(this);
    fetch('submit_feedback.php', {
        method: 'POST',
        body: formData
    }).then(res => res.text()).then(response => {
        if (response.trim() === 'success') {
            alert("Thanks for your feedback!");
            location.reload();
        } else {
            alert("Failed to submit feedback.");
        }
    });
});
</script>
<script>
fetch('get_weather.php?city=<?= urlencode($package["location"]) ?>')
  .then(res => res.json())
  .then(data => {
    const el = document.getElementById("weather-<?= $package['id']; ?>");
    if (data.temp) {
      el.innerHTML = `
        <strong>Current Weather:</strong><br>
        <img src="https://openweathermap.org/img/wn/${data.icon}@2x.png" width="40" />
        ${data.temp}°C – ${data.description}
      `;
    } else {
      el.innerText = "Weather info unavailable.";
    }
  })
  .catch(err => {
    console.error(err);
    document.getElementById("weather-<?= $package['id']; ?>").innerText = "Error fetching weather.";
  });
</script>

<?php include 'footer.php'; ?>

