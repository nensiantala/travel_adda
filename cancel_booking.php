<?php
session_start();
include 'db_connect.php';

// Check login and booking ID
if (!isset($_SESSION['customer_id']) || !isset($_GET['booking_id'])) {
    header("Location: customer_dashboard.php");
    exit();
}

$booking_id = intval($_GET['booking_id']);
$customer_id = $_SESSION['customer_id'];

// Update booking status to 'cancelled'
$stmt = $conn->prepare("UPDATE bookings SET status = 'cancelled' WHERE id = ? AND customer_id = ?");
$stmt->bind_param("ii", $booking_id, $customer_id);
$stmt->execute();
$stmt->close();

header("Location: customer_dashboard.php?cancelled=1");
exit();
?>
