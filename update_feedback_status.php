<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $feedback_id = intval($_POST['feedback_id']);
    $status = $_POST['status'];

    if (in_array($status, ['approved', 'pending', 'blocked'])) {
        $stmt = $conn->prepare("UPDATE feedback SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $feedback_id);
        $stmt->execute();
    }
}
header("Location: manage_feedbacks.php");
exit();
