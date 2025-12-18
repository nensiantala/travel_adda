<?php
session_start();
if (!isset($_SESSION['customer_id'])) {
    header("Location: customer_login.php");
    exit();
}

include 'db_connect.php';
include 'header.php';

// Fetch all active packages
$sql = "SELECT * FROM packages WHERE status = 'active'";
$result = $conn->query($sql);

$customer_name = $_SESSION['customer_username'];

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
?>


<!DOCTYPE html>
<html>
<head>
    <title>All Travel Packages - Travel Adda</title>
    <link rel="stylesheet" href="style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
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



.show-more-link {
  color: #17B978;
  cursor: pointer;
  font-weight: 500;
  /* margin-top: 4px; */
  display: inline-block;
  font-size: 0.9rem;
}

        .card-text {
  font-size: 0.95rem;
  color: #555;
  margin-bottom: 12px;
  line-height: 1.5;
}

.short-desc,
.full-desc {
  font-size: 0.95rem;
  color: #444;
  line-height: 1.5;
}

.short-desc {
  display: inline;
}

.full-desc {
  display: none;  /* hidden by default */
}

.show-more-link {
  color: #17B978;
  cursor: pointer;
  font-weight: 500;
  display: inline-block;
  font-size: 0.9rem;
}

.show-more-link:hover {
  text-decoration: underline;
  color: #129966;
}


.toggle-link {
  color: #17B978;
  cursor: pointer;
  font-weight: 600;
  margin-left: 6px;
  text-decoration: none;
}

.toggle-link:hover {
  color: #129966;
  text-decoration: underline;
}

        .card:hover {
            transform: scale(1.02);
            transition: transform 0.3s;
        }
        .carousel-inner img {
            height: 200px;
            object-fit: cover;
        }
        .card {
  width: 100%;
  max-width: 350px; /* Wider than before */
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
  /* padding: 20px 15px; */
}
.rating-badge {
    background: rgba(0, 0, 0, 0.6);
    font-weight: 500;
    font-size: 0.85rem;
    backdrop-filter: blur(3px);
    border-radius: 8px;
}
    </style>
</head>
<body>

<div class="container mt-4">
    <h2 class="mb-4 text-center">All Available Travel Packages</h2>
    <form method="GET" class="search-wrapper">
        <input type="text" name="search" class="search-bar" placeholder="Search by title" value="<?= htmlspecialchars($search); ?>">
        <button type="submit" class="btn-search">Search</button>
    </form> </div>
<div class="row g-4">
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="col-md-3"> <!-- ✅ This ensures 3 per row on large screens -->
                <div class="card <?= ($row['status'] != 'active') ? 'blurred' : '' ?> h-100">
                    
                    <!-- Image carousel -->
                    <div id="carousel<?= $row['id'] ?>" class="carousel slide position-relative" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php for ($i = 1; $i <= 4; $i++): ?>
                                <?php if (!empty($row["image$i"])): ?>
                                    <div class="carousel-item <?= $i == 1 ? 'active' : '' ?>">
                                        <img src="assets/<?= htmlspecialchars($row["image$i"]); ?>" 
                                             class="d-block w-100 rounded-top" 
                                             height="200px" 
                                             alt="Image <?= $i ?>">
                                    </div>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>

                        <!-- Carousel controls -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#carousel<?= $row['id'] ?>" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carousel<?= $row['id'] ?>" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>

                        <!-- Rating badge -->
                        <div class="rating-badge position-absolute top-0 end-0 m-2 px-2 py-1 rounded d-flex align-items-center">
                            <span class="me-1" style="color: #ffc107;">&#9733;</span>
                            <span class="text-white small">
                                <?= isset($row['avg_rating']) && $row['avg_rating'] !== null && $row['avg_rating'] !== ''
                                    ? htmlspecialchars($row['avg_rating']) . ' / 5'
                                    : 'No ratings' ?>
                            </span>
                        </div>
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
        <?php endwhile; ?>
    <?php else: ?>
        <div class="col-12 text-center">
            <p class="text-danger">No packages found!</p>
        </div>
    <?php endif; ?>
</div><br>
<div class="text-center mt-3">
    <a href="customer_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
</div>
       
<script>
function toggleDescription(el) {
    const wrapper = el.closest('.desc-wrapper');
    const shortDesc = wrapper.querySelector('.short-desc');
    const fullDesc = wrapper.querySelector('.full-desc');

    if (fullDesc.style.display === 'block') {
        fullDesc.style.display = 'none';
        shortDesc.style.display = 'inline';
        el.textContent = 'Show more';
    } else {
        fullDesc.style.display = 'block';
        shortDesc.style.display = 'none';
        el.textContent = 'Show less';
    }
}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include "footer.php"; ?>
