<!DOCTYPE html>
<html>
<head>
  <title><?= isset($page_title) ? $page_title : 'Travel Adda' ?></title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="customer_dashboard.php">
            <img src="image/logo.png" alt="Travel Adda Logo" height="40" class="me-2">
            <span class="fw-bold text-white">Travel Adda</span>
        </a>
        <div class="d-flex ms-auto align-items-center gap-3">
            <a href="packages.php" class="text-white text-decoration-none">Packages</a>
            <a href="about.php" class="text-white text-decoration-none">About Us</a>
            <a href="contact.php" class="text-white text-decoration-none">Contact Us</a>
            <a href="my_bookings.php" class="btn btn-outline-primary">View My Bookings</a>
            <a href="logout.php" class="btn btn-light">Logout</a>
        </div>
    </div>
</nav>
<div class="container mt-4">
</div>
</body>
</html>

