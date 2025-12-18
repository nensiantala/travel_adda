<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style/style.css">
    <style>
        /* Import Google Font */
@import url('https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;700&display=swap');

body {
    margin: 0;
    padding: 0;
    font-family: 'Nunito Sans', sans-serif;
    background: linear-gradient(to bottom right,rgba(226, 226, 226, 0.6),rgba(126, 126, 126, 0.59));
    color: #333;
    min-height: 100vh;
}

h1 {
    font-size: 2rem;
    color: #17B978;
    margin-top: 2rem;
    text-align: center;
}

p {
    font-size: 1.1rem;
    text-align: center;
    color: #666;
}

h2 {
    font-size: 1.5rem;
    text-align: center;
    margin-top: 2rem;
    color: #333;
}

ul {
    list-style: none;
    padding: 0;
    margin: 2rem auto;
    max-width: 400px;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

ul li {
    background-color: white;
    border-radius: 10px;
    padding: 1rem 1.5rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.07);
    transition: transform 0.2s ease, box-shadow 0.3s ease;
    text-align: center;
}

ul li:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 15px rgba(0, 68, 90, 0.31);
}

ul li a {
    text-decoration: none;
    color: #17B978;
    font-weight: 600;
    letter-spacing: 0.5px;
    font-size: 1rem;
    transition: color 0.3s ease;
}

ul li a:hover {
    color: #00435a;
    
}
.container{
    background-color: rgba(218, 250, 239, 0.77);
    margin: 50px ;
    border-radius: 10px;
    padding-bottom: 10px;
    padding-top: 10px;
}
/* Responsive */
@media (max-width: 600px) {
    ul {
        max-width: 90%;
    }

    h1, h2 {
        font-size: 1.5rem;
    }

    ul li a {
        font-size: 0.95rem;
    }
}

    </style>
</head>
<body>
    <div class="container">
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</h1>
    <p>This is the admin dashboard.</p>

    <h2>Admin Actions</h2>
    <ul>
        <li><a href="add_package.php">Add Package</a></li>
        <li><a href="manage_packages.php">Manage Packages</a></li>
        <li><a href="view_bookings.php">View Bookings</a></li>
        <li><a href="admin_manage_users.php">Manage Users</a></li>
        <li><a href="manage_feedbacks.php">Manage Feedbacks</a></li>
    </ul>
</div>
</body>
</html>
