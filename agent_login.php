<?php
session_start();
include 'db_connect.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM agents WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $agent = $result->fetch_assoc();
        if (password_verify($password, $agent['password'])) {
            $_SESSION['agent_id'] = $agent['id'];
            $_SESSION['agent_email'] = $agent['email'];
            header("Location: agent_dashboard.php");
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "Invalid email.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Agent Login - Travel Adda</title>
    <link rel="stylesheet" href="style/logincss.css">
    <style>
        * {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}

form input {
  width: 100%;
  padding: 12px;
  margin: 10px 0;
  border: 1px solid #ccc;
  border-radius: 8px;
  transition: border-color 0.3s;
}

form input:focus {
  border-color: #17B978;
  outline: none;
}

body {
    background: url('image/logbg.png') no-repeat center center fixed;
     display: flex;
    justify-content: center; /* Horizontal */
    align-items: center;     /* Vertical */
    height: 100vh;
    /* background-size: cover; */
}

/* Centering container */
.container {
    width: 450px;
    background: rgba(255, 255, 255, 0.26);
    backdrop-filter: blur(10px);
    border-radius: 10px;
    padding: 30px;
    text-align: center;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
}

/* Headings */
h2 {
    margin-bottom: 20px;
    color: #777777ff;
}

/* Input fields */
input[type="text"],
input[type="password"],
input[type="email"] {
    width: 90%;
    padding: 12px;
    margin: 10px 0;
    border: none;
    outline: none;
    border-radius: 6px;
}

/* Buttons */
button {
    width: 100%;
    padding: 12px;
    background: linear-gradient(90deg, #17B978, #46a780ff);
    border: none;
    border-radius: 6px;
    color: #fff;
    font-size: 16px;
    cursor: pointer;
    margin-top: 10px;
}

button:hover {
    background: linear-gradient(90deg, #4c8fe0, #4cb8e0);
}

/* Error text */
.error {
    color: #ff8080;
    margin-bottom: 10px;
}

/* Links */
p, a {
    color: #0c4b31ff;
    font-size: 14px;
}

a:hover {
    text-decoration: underline;
}

    </style>
</head>
<body>
<div class="container">
    <h2>Agent Login</h2>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form><br>
    <p>Don't have an agent account? <a href="agent_register.php">Register here</a></p>
<p><a href="agent_forgot_password.php">Forgot Password?</a></p>
</div>
</body>
</html>
