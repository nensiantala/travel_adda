<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: customer_login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];
$stmt = $conn->prepare("SELECT username, email FROM customers WHERE id = ?");
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();
?>

<h2>My Profile</h2>
<p><strong>Username:</strong> <?= htmlspecialchars($customer['username']) ?></p>
<p><strong>Email:</strong> <?= htmlspecialchars($customer['email']) ?></p>
<a href="edit_profile.php">Edit Profile</a>

<?php include "footer.php"; ?>
