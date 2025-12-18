<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: customer_login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);

    $stmt = $conn->prepare("UPDATE customers SET username = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssi", $username, $email, $customer_id);
    $stmt->execute();

    $_SESSION['customer_username'] = $username; // Update session too
    header("Location: my_profile.php");
    exit();
} else {
    $stmt = $conn->prepare("SELECT username, email FROM customers WHERE id = ?");
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $customer = $result->fetch_assoc();
}
?>

<h2>Edit Profile</h2>
<form method="POST">
    Username: <input type="text" name="username" value="<?= htmlspecialchars($customer['username']) ?>" required><br><br>
    Email: <input type="email" name="email" value="<?= htmlspecialchars($customer['email']) ?>" required><br><br>
    <button type="submit">Update</button>
</form>
