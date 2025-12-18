<?php
session_start();
include 'db_connect.php';

// Check if customer is logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: customer_login.php");
    exit();
}

// Check if package_id is passed
if (!isset($_GET['package_id'])) {
    header("Location: customer_dashboard.php");
    exit();
}

$package_id = intval($_GET['package_id']);
$customer_id = $_SESSION['customer_id'];

// Step 1: Verify if the package exists and is active
$checkStmt = $conn->prepare("SELECT id FROM packages WHERE id = ? AND status = 'active'");
$checkStmt->bind_param("i", $package_id);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows === 0) {
    // Package is not found or inactive
    $checkStmt->close();
    header("Location: customer_dashboard.php?error=invalid_package");
    exit();
}
$checkStmt->close();

// Step 2: Insert booking
$insertStmt = $conn->prepare("INSERT INTO bookings (customer_id, package_id, booking_date, status) VALUES (?, ?, NOW(), 'pending')");
if ($insertStmt) {
    $insertStmt->bind_param("ii", $customer_id, $package_id);
    if ($insertStmt->execute()) {
        $insertStmt->close();

        // Optional: Fetch customer email for future mailing
        /*
        $emailStmt = $conn->prepare("SELECT email FROM customers WHERE id = ?");
        $emailStmt->bind_param("i", $customer_id);
        $emailStmt->execute();
        $emailResult = $emailStmt->get_result();
        if ($emailResult->num_rows > 0) {
            $emailData = $emailResult->fetch_assoc();
            $email = $emailData['email'];
            // Send email using PHPMailer (to be added)
        }
        $emailStmt->close();
        */

        header("Location: customer_dashboard.php?booked=1");
        exit();
    } else {
        $insertStmt->close();
        header("Location: customer_dashboard.php?error=booking_failed");
        exit();
    }
} else {
    header("Location: customer_dashboard.php?error=db_prepare_failed");
    exit();
}
?>
