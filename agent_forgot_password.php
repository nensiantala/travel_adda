<?php
session_start();
include 'db_connect.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);

    // Check if agent exists
    $stmt = $conn->prepare("SELECT * FROM agents WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $_SESSION['reset_email'] = $email;
        header("Location: agent_reset_password.php");
        exit();
    } else {
        $message = "No account found with that email.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password - Travel Adda</title>
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
    <h2>Forgot Password</h2>
    <?php if(!empty($message)) echo "<p class='msg'>$message</p>"; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Enter your registered email" required>
        <button type="submit">Continue</button>
    </form>
</div>
</body>
</html>
