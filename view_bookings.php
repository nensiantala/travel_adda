<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db_connect.php';

// Correct SQL using correct alias and table names
$sql = "SELECT b.id, b.booking_date, c.username AS customer_name, p.title AS package_title, b.status
        FROM bookings b
        JOIN customers c ON b.customer_id = c.id
        JOIN packages p ON b.package_id = p.id
        ORDER BY b.booking_date DESC";


$result = $conn->query($sql);
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <link rel="stylesheet" href="style/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;700&display=swap" rel="stylesheet">
<style>
    * {
        box-sizing: border-box;
    }

    body {
        font-family: 'Nunito Sans', sans-serif;
        margin: 0;
        padding: 0;
        background: linear-gradient(to bottom right, #e6f9f6, #d7f0f6);
        color: #00435a;
    }

    .container {
        max-width: 1100px;
        margin: 3rem auto;
        background: #ffffff;
        padding: 2.5rem 3rem;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        animation: fadeIn 0.6s ease;
    }

    h2 {
        font-size: 2rem;
        text-align: center;
        margin-bottom: 2rem;
        color: #00435a;
    }

    .btn {
        display: inline-block;
        padding: 0.6rem 1.2rem;
        border: none;
        background-color: #17B978;
        color: white;
        text-decoration: none;
        font-weight: 600;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        transition: background-color 0.3s ease;
    }

    .btn:hover {
        background-color: #149f68;
    }

    table {
    width: 100%;
    border-collapse: separate; /* Important for border-radius */
    border-spacing: 0;
    border-radius: 15px;
    overflow: hidden;
    animation: fadeInUp 0.5s ease;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

thead {
    background-color: #17B978;
    color: white;
}

th, td {
    padding: 1rem;
    text-align: center;
    border-bottom: 1px solid #ddd;
}

/* Apply rounded corners manually */
thead tr th:first-child {
    border-top-left-radius: 15px;
}
thead tr th:last-child {
    border-top-right-radius: 15px;
}
tbody tr:last-child td:first-child {
    border-bottom-left-radius: 15px;
}
tbody tr:last-child td:last-child {
    border-bottom-right-radius: 15px;
}

    /* Animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.98);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container {
            padding: 1.5rem;
        }

        table, thead, tbody, th, td, tr {
            display: block;
        }

        thead {
            display: none;
        }

        tr {
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 1rem;
            background-color: white;
        }

        td {
            text-align: right;
            padding: 0.5rem;
            position: relative;
        }

        td::before {
            content: attr(data-label);
            position: absolute;
            left: 1rem;
            width: 50%;
            text-align: left;
            font-weight: bold;
            color: #00435a;
        }
    }
</style>

</head>
<body>
    <div class="container mt-5">
    <h2 class="mb-4">All Customer Bookings</h2>
    <a href="admin_dashboard.php" class="btn btn-secondary mb-3">← Back to Dashboard</a>
    <table class="table table-bordered table-striped">
        <thead class="table-success">
            <tr>
                <th>Booking ID</th>
                <th>Customer Name</th>
                <th>Package</th>
                <th>Booking Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <!-- DEBUGGING (OPTIONAL) -->
                <!-- <?php var_dump($row); ?> -->

                <tr>
                    <td data-label="Booking ID"><?= htmlspecialchars($row['id']) ?></td>
<td data-label="Customer Name"><?= htmlspecialchars($row['customer_name']) ?></td>
<td data-label="Package"><?= htmlspecialchars($row['package_title']) ?></td>
<td data-label="Booking Date"><?= htmlspecialchars($row['booking_date']) ?></td>
<td data-label="Status">
    <?= htmlspecialchars($row['status']) ?>
</td>
<td data-label="Action">
    <?php if ($row['status'] == 'pending'): ?>
        <form action="update_booking_status.php" method="POST" style="display:inline-block;">
            <input type="hidden" name="booking_id" value="<?= $row['id'] ?>">
            <button type="submit" name="action" value="accept" class="btn btn-success btn-sm">Accept</button>
            <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">Reject</button>
        </form>
    <?php else: ?>
        <span class="text-muted">N/A</span>
    <?php endif; ?>
</td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4" class="text-center text-danger">No bookings found</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

</body>
</html>


