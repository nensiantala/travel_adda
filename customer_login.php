<?php
session_start();
include 'db_connect.php';

$error = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM customers WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $customer = $result->fetch_assoc();
        if (password_verify($password, $customer['password'])) {
            $_SESSION['customer_id'] = $customer['id'];
            $_SESSION['customer_username'] = $customer['username'];
            header("Location: customer_dashboard.php");
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "Invalid username.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Customer Login - Travel Adda</title>
    <!-- <link rel="stylesheet" href="style/logincss.css"> -->
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
      display: flex;
    justify-content: center; /* Horizontal */
    align-items: center;     /* Vertical */
    height: 100vh;
    background: url('image/logbg.png') no-repeat center center fixed;
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
    <h2>Customer Login</h2>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form><br>
    <p>Don't have an account? <a href="customer_register.php">Register here</a></p>
    <p><a href="customer_forgot_password.php">Forgot Password?</a></p>

</div>
</body>
</html>
