<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Get all users with their booked packages (if any)
$sql = "SELECT c.id, c.username, c.email, p.title AS package_title
        FROM customers c
        LEFT JOIN bookings b ON c.id = b.customer_id
        LEFT JOIN packages p ON b.package_id = p.id
        ORDER BY c.id DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <!-- Font & Custom Style -->
<link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;700&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Nunito Sans', sans-serif;
        background: linear-gradient(to bottom right, #e6f9f6, #d7f0f6);
        color: #00435a;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 1100px;
        margin: 50px auto;
        padding: 30px;
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        animation: fadeIn 0.6s ease-in-out;
    }

    h2 {
        text-align: center;
        color: #00435a;
        font-weight: 700;
        margin-bottom: 30px;
        animation: slideDown 0.6s ease;
    }

    .btn-secondary {
        background-color: #17B978;
        border: none;
        font-weight: 600;
        padding: 8px 16px;
        margin-bottom: 20px;
        border-radius: 8px;
        color: #fff;
        text-decoration: none;
        display: inline-block;
        transition: background 0.3s ease;
    }

    .btn-secondary:hover {
        background-color: #149f68;
        color: white;
    }

  /* Fix border radius */
table {
        width: 100%;
        border-collapse: collapse;
        background-color: #ffffff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        animation: fadeInTable 0.8s ease-in-out;
    }

    th, td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #eee;
    }
thead tr th {
    background-color: #17B978 !important;
    color: white !important;
}

    thead {
        background-color: #17B978;
        color: white;
        font-size: 1rem;
    }
    tr{
         background-color: #17B978;
        color: white;
        font-size: 1rem;
    }
    tr:hover {
        background-color: #f0fbf7;
    }

/* Round only top-left and top-right header cells */
.table thead th:first-child {
    border-top-left-radius: 12px;
}
.table thead th:last-child {
    border-top-right-radius: 12px;
}

/* Round bottom-left and bottom-right on last row */
.table tbody tr:last-child td:first-child {
    border-bottom-left-radius: 12px;
}
.table tbody tr:last-child td:last-child {
    border-bottom-right-radius: 12px;
}


    .btn-danger {
        padding: 5px 10px;
        font-size: 0.875rem;
        border: none;
        border-radius: 6px;
        background-color: #dc3545;
        color: white;
        transition: background 0.3s ease;
    }

    .btn-danger:hover {
        background-color: #b02a37;
    }

    em {
        color: #999;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-30px); }
        to   { opacity: 1; transform: translateY(0); }
    }
</style>



</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">User Management</h2>
    <a href="admin_dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

    <table class="table"  border="0.8" cellpadding="10">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Booked Package</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= $row['package_title'] ?? '<em>No Booking</em>' ?></td>
                    <td>
                        <a href="delete_user.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this user?')" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5" class="text-center text-danger">No users found</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
