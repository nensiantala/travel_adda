<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db_connect.php';

// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = intval($_POST['booking_id']);
    $action = $_POST['action']; // 'accept' or 'reject'
    
    if (!in_array($action, ['accept', 'reject'])) {
        die("Invalid action.");
    }

    // Fetch booking info with customer email
    $stmt = $conn->prepare("SELECT b.id, b.status, c.email, c.username, p.title
                            FROM bookings b
                            JOIN customers c ON b.customer_id = c.id
                            JOIN packages p ON b.package_id = p.id
                            WHERE b.id = ?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();
    $stmt->close();

    if (!$booking) {
        die("Booking not found.");
    }

    // Update booking status
    $newStatus = ($action === 'accept') ? 'accepted' : 'rejected';
    $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $newStatus, $booking_id);
    $stmt->execute();
    $stmt->close();

    // Send email to customer
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'yourgmail@gmail.com';       // ✅ Your Gmail
        $mail->Password   = 'your_app_password';         // ✅ App Password (not your Gmail password)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('yourgmail@gmail.com', 'Travel Adda');
        $mail->addAddress($booking['email'], $booking['username']);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Booking ' . ucfirst($newStatus);
        $mail->Body    = "Hello <strong>" . htmlspecialchars($booking['username']) . "</strong>,<br><br>"
                       . "Your booking for <strong>" . htmlspecialchars($booking['title']) . "</strong> has been <strong>$newStatus</strong> by the admin.<br><br>"
                       . "Thank you for using Travel Adda.";

        $mail->send();
        // Optional: Add success message to session
        $_SESSION['mail_status'] = "Email sent successfully.";
    } catch (Exception $e) {
        // Optional: Add error message to session
        $_SESSION['mail_status'] = "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

    // Redirect back
    header("Location: view_bookings.php");
    exit();
}
?>

