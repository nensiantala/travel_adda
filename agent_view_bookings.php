<?php
session_start();
if (!isset($_SESSION['agent_id'])) {
    header("Location: agent_login.php");
    exit();
}

include 'db_connect.php';
$agent_id = $_SESSION['agent_id'];

// Fetch bookings for packages owned by this agent
$sql = "SELECT b.id, b.booking_date, c.username AS customer_name, p.title AS package_title, b.status
        FROM bookings b
        JOIN customers c ON b.customer_id = c.id
        JOIN packages p ON b.package_id = p.id
        WHERE p.agent_id = ?
        ORDER BY b.booking_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $agent_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agent Bookings - Travel Adda</title>
    <link rel="stylesheet" href="../style/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito Sans', sans-serif;
            background: linear-gradient(to bottom right, #e6f9f6, #d7f0f6);
            color: #00435a;
            padding: 2rem;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            background: #fff;
            padding: 2.5rem 3rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 2rem;
            color: #00435a;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            font-weight: 600;
            border-radius: 8px;
            text-decoration: none;
            margin-right: 5px;
            cursor: pointer;
        }

        .btn-success {
            background-color: #17B978;
            color: white;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 15px;
            overflow: hidden;
            margin-top: 20px;
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

        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }

            thead {
                display: none;
            }

            tr {
                margin-bottom: 1rem;
                background-color: white;
                border: 1px solid #ccc;
                border-radius: 10px;
                padding: 1rem;
            }

            td {
                text-align: right;
                position: relative;
                padding: 0.5rem;
            }

            td::before {
                content: attr(data-label);
                position: absolute;
                left: 1rem;
                font-weight: bold;
                color: #00435a;
                text-align: left;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>My Package Bookings</h2>
        <a href="agent_dashboard.php" class="btn btn-secondary mb-3">← Back to Dashboard</a>

        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Customer</th>
                    <th>Package</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td data-label="Booking ID"><?= htmlspecialchars($row['id']) ?></td>
                        <td data-label="Customer"><?= htmlspecialchars($row['customer_name']) ?></td>
                        <td data-label="Package"><?= htmlspecialchars($row['package_title']) ?></td>
                        <td data-label="Date"><?= htmlspecialchars($row['booking_date']) ?></td>
                        <td data-label="Status"><?= htmlspecialchars($row['status']) ?></td>
                        <td data-label="Action">
                            <?php if ($row['status'] === 'pending'): ?>
                                <form action="agent_update_booking_status.php" method="POST" style="display:inline-block;">
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
                <tr><td colspan="6" class="text-center text-danger">No bookings found</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
