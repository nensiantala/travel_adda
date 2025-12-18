<?php
include 'header.php';
include 'db_connect.php';

$result = $conn->query("SELECT * FROM packages");
?>

<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="style.css">


</head>
<body>
  <h2>Welcome to Travel Adda</h2>

<div class="row">
<?php while($row = $result->fetch_assoc()): ?>
  <div class="card" style="width: 18rem; margin: 10px;">
    <img src="assets/<?php echo $row['image']; ?>" class="card-img-top" height="200px">
    <div class="card-body">
      <h5 class="card-title"><?php echo $row['title']; ?></h5>
      <p class="card-text"><?php echo $row['description']; ?></p>
      <p><strong><?php echo $row['duration']; ?> | ₹<?php echo $row['price']; ?></strong></p>
      <?php if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'customer'): ?>
        <a href="customer_login.php" class="btn btn-primary">Login to Book</a>
      <?php else: ?>
        <a href="book.php?package_id=<?php echo $row['id']; ?>" class="btn btn-success">Book Now</a>
      <?php endif; ?>
    </div>
  </div>
<?php endwhile; ?>
</div>

</body>
</html>

