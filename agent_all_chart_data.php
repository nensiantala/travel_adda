<?php
session_start();
include 'db_connect.php';

$agent_id = $_SESSION['agent_id'] ?? 0;

$data = [
    "monthly_bookings" => [],
    "monthly_revenue" => [],
    "status_distribution" => [],
    "popular_packages" => []
];

// 1. Monthly Bookings
$bookingQuery = $conn->prepare("
    SELECT DATE_FORMAT(b.booking_date, '%Y-%m') AS month, COUNT(*) AS total
    FROM bookings b
    JOIN packages p ON b.package_id = p.id
    WHERE p.agent_id = ?
    GROUP BY month
");
$bookingQuery->bind_param("i", $agent_id);
$bookingQuery->execute();
$bookingResult = $bookingQuery->get_result();
while ($row = $bookingResult->fetch_assoc()) {
    $data['monthly_bookings'][] = $row;
}
$bookingQuery->close();

// 2. Monthly Revenue
$revenueQuery = $conn->prepare("
    SELECT DATE_FORMAT(b.booking_date, '%Y-%m') AS month, SUM(p.price) AS revenue
    FROM bookings b
    JOIN packages p ON b.package_id = p.id
    WHERE p.agent_id = ? AND b.status = 'accepted'
    GROUP BY month
");
$revenueQuery->bind_param("i", $agent_id);
$revenueQuery->execute();
$revenueResult = $revenueQuery->get_result();
while ($row = $revenueResult->fetch_assoc()) {
    $data['monthly_revenue'][] = $row;
}
$revenueQuery->close();

// 3. Status Distribution
$statusQuery = $conn->prepare("
    SELECT b.status, COUNT(*) AS count
    FROM bookings b
    JOIN packages p ON b.package_id = p.id
    WHERE p.agent_id = ?
    GROUP BY b.status
");
$statusQuery->bind_param("i", $agent_id);
$statusQuery->execute();
$statusResult = $statusQuery->get_result();
while ($row = $statusResult->fetch_assoc()) {
    $data['status_distribution'][] = $row;
}
$statusQuery->close();

// 4. Popular Packages
$popularQuery = $conn->prepare("
    SELECT p.title, COUNT(*) AS total
    FROM bookings b
    JOIN packages p ON b.package_id = p.id
    WHERE p.agent_id = ?
    GROUP BY b.package_id
    ORDER BY total DESC
    LIMIT 5
");
$popularQuery->bind_param("i", $agent_id);
$popularQuery->execute();
$popularResult = $popularQuery->get_result();
while ($row = $popularResult->fetch_assoc()) {
    $data['popular_packages'][] = $row;
}
$popularQuery->close();

// Output all data as JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
