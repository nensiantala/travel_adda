<?php
session_start();
if (!isset($_SESSION['agent_id'])) {
    header("Location: agent_login.php");
    exit();
}

include 'db_connect.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = intval($_POST['booking_id']);
    $action = $_POST['action'];

    if (!in_array($action, ['accept', 'reject'])) {
        die("Invalid action.");
    }

    // Fetch booking + agent verification
    $agent_id = $_SESSION['agent_id'];
    $stmt = $conn->prepare("SELECT b.id, b.status, c.email, c.username, p.title, p.agent_id
                            FROM bookings b
                            JOIN customers c ON b.customer_id = c.id
                            JOIN packages p ON b.package_id = p.id
                            WHERE b.id = ?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();
    $stmt->close();

    if (!$booking || $booking['agent_id'] != $agent_id) {
        die("Unauthorized or invalid booking.");
    }

    // Update status
    $newStatus = ($action === 'accept') ? 'accepted' : 'rejected';
    $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $newStatus, $booking_id);
    $stmt->execute();
    $stmt->close();

    // Send email to customer
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'yourgmail@gmail.com';       // ✅ Your Gmail
        $mail->Password   = 'your_app_password';         // ✅ App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('yourgmail@gmail.com', 'Travel Adda');
        $mail->addAddress($booking['email'], $booking['username']);

        $mail->isHTML(true);
        $mail->Subject = 'Booking ' . ucfirst($newStatus);
        $mail->Body    = "Hello <strong>" . htmlspecialchars($booking['username']) . "</strong>,<br><br>"
                       . "Your booking for <strong>" . htmlspecialchars($booking['title']) . "</strong> has been <strong>$newStatus</strong> by the agent.<br><br>"
                       . "Thank you for choosing Travel Adda.";

        $mail->send();
        $_SESSION['mail_status'] = "Email sent successfully.";
    } catch (Exception $e) {
        $_SESSION['mail_status'] = "Email could not be sent. Error: {$mail->ErrorInfo}";
    }

    header("Location: agent_manage_bookings.php");
    exit();
}
?>
