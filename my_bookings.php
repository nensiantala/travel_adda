<?php include "header.php"; ?>
<?php
session_start();
if (!isset($_SESSION['customer_id'])) {
    header("Location: customer_login.php");
    exit();
}

include 'db_connect.php';

$customer_id = $_SESSION['customer_id'];

$result = $conn->query("SELECT b.id, b.booking_date, p.title, b.status 
                        FROM bookings b
                        JOIN packages p ON b.package_id = p.id
                        WHERE b.customer_id = $customer_id
                        ORDER BY b.booking_date DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Bookings - Travel Adda</title>
    <link rel="stylesheet" href="style/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito Sans', sans-serif;
            background-color: #f8f9fa;
        }
        .btn-cancel {
            background-color: #dc3545;
            color: white;
        }
        .btn-cancel:hover {
            background-color: #c82333;
        }
        .status-cancelled {
            color: red;
            font-weight: bold;
        }
        .status-active {
            color: green;
            font-weight: bold;
        }
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

    tr:hover {
        background-color: #f0fbf7;
    }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4 text-center">My Bookings</h2>

    <?php if (isset($_GET['cancelled']) && $_GET['cancelled'] == 1): ?>
        <div class="alert alert-success text-center">Booking cancelled successfully.</div>
    <?php endif; ?>

    <table class="table">
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Package</th>
                <th>Booking Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= $row['booking_date'] ?></td>
                       <td>
    <?php
    switch ($row['status']) {
        case 'pending':
            echo '<span class="text-warning fw-bold">Pending</span>';
            break;
        case 'accepted':
            echo '<span class="text-success fw-bold">Accepted</span>';
            break;
        case 'rejected':
            echo '<span class="text-danger fw-bold">Rejected</span>';
            break;
        case 'cancelled':
            echo '<span class="status-cancelled">Cancelled</span>';
            break;
        default:
            echo '<span class="status-active">Active</span>';
    }
    ?>
</td>
<td>
    <?php if ($row['status'] === 'pending' || $row['status'] === 'accepted' || $row['status'] === 'active'): ?>
        <a href="cancel_booking.php?booking_id=<?= $row['id'] ?>" 
           class="btn btn-sm btn-cancel"
           onclick="return confirm('Are you sure you want to cancel this booking?')">
            Cancel
        </a>
    <?php else: ?>
        <span class="text-muted">N/A</span>
    <?php endif; ?>
</td>

                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center text-danger">No bookings found!</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
<?php include "footer.php"; ?>
