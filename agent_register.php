<?php
include 'db_connect.php';

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $company = trim($_POST['company_name']);
    $employee_count = intval($_POST['employee_count']);

    $check = $conn->prepare("SELECT id FROM agents WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $error = "Email already registered.";
    } else {
        $stmt = $conn->prepare("INSERT INTO agents (email, phone, password, company_name, employee_count) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $email, $phone, $password, $company, $employee_count);

        if ($stmt->execute()) {
            $success = "Registered successfully! You can now log in.";
        } else {
            $error = "Registration failed.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Agent Register - Travel Adda</title>
    <!-- <link rel="stylesheet" href="style/logincss.css"> -->
    <style>
        .styled-select {
    width: 90%;
    padding: 12px;
  margin: 10px 0;
  border: 1px solid #ccc;
  border-radius: 8px;
  transition: border-color 0.3s;
    background-color: #fff;
    color:rgb(134, 134, 134);
    font-size: 13px;
    margin-bottom: 15px;
    appearance: none; /* Removes default arrow in some browsers */
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D'10'%20height%3D'10'%20viewBox%3D'0%200%2010%2010'%20xmlns%3D'http://www.w3.org/2000/svg'%3E%3Cpath%20d%3D'M0%202l5%205%205-5z'%20fill%3D'%2317B978'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 12px;
}

.styled-select:focus {
    border-color: #17B978;
    box-shadow: 0 0 0 3px rgba(23, 185, 120, 0.25);
    outline: none;
}
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
    <h2>Agent Registration</h2>
    <?php if ($success) echo "<p class='success'>$success</p>"; ?>
    <?php if ($error) echo "<p class='error'>$error</p>"; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Email (Gmail)" required><br>
        <input type="text" name="phone" placeholder="Phone Number" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="text" name="company_name" placeholder="Company Name" required><br>
        <select name="employee_count" required class="styled-select">
            <option value="">Select Employee Count</option>
            <option value="1">1-10</option>
            <option value="2">11-50</option>
            <option value="3">51-100</option>
            <option value="4">100+</option>
        </select><br>
        <button type="submit">Register</button>
    </form><br>
    <p>Already an agent? <a href="agent_login.php">Login here</a></p>
</div>
</body>
</html>
