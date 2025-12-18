<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['customer_id'])) {
    echo "unauthorized";
    exit();
}

$customer_id = $_SESSION['customer_id'];
$package_id = intval($_POST['package_id']);
$rating = intval($_POST['rating']);
$comment = trim($_POST['comment']);

// Insert feedback without any duplicate check
$stmt = $conn->prepare("INSERT INTO feedback (customer_id, package_id, rating, comment, status) VALUES (?, ?, ?, ?, 'pending')");
$stmt->bind_param("iiis", $customer_id, $package_id, $rating, $comment);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "error: " . $stmt->error;
}
?>
