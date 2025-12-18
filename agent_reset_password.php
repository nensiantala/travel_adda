<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['reset_email'])) {
    header("Location: agent_forgot_password.php");
    exit();
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password === $confirm) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $email = $_SESSION['reset_email'];

        $stmt = $conn->prepare("UPDATE agents SET password=? WHERE email=?");
        $stmt->bind_param("ss", $hashed, $email);
        if ($stmt->execute()) {
            unset($_SESSION['reset_email']);
            header("Location: agent_login.php?reset=success");
            exit();
        } else {
            $message = "Something went wrong. Try again.";
        }
    } else {
        $message = "Passwords do not match.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password - Travel Adda</title>
    <style>
        body {font-family: Arial, sans-serif; display:flex; justify-content:center; align-items:center; height:100vh; background:#f3f3f3;}
        .box {background:#fff; padding:30px; border-radius:10px; box-shadow:0 0 10px rgba(0,0,0,.2); width:400px;}
        input {width:100%; padding:12px; margin:10px 0; border:1px solid #ccc; border-radius:6px;}
        button {width:100%; padding:12px; background:#17B978; border:none; color:white; border-radius:6px; cursor:pointer;}
        button:hover {background:#139d66;}
        .msg {color:red;}
    </style>
</head>
<body>
<div class="box">
    <h2>Reset Password</h2>
    <?php if(!empty($message)) echo "<p class='msg'>$message</p>"; ?>
    <form method="POST">
        <input type="password" name="password" placeholder="New Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <button type="submit">Reset Password</button>
    </form>
</div>
</body>
</html>
