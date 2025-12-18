<?php
session_start();
include "db_connect.php";

if (!isset($_POST['booking_id'])) {
    die("Invalid Request");
}
$booking_id = $_POST['booking_id'];

// Update booking status
$sql = "UPDATE bookings SET payment_status='Paid' WHERE id='$booking_id'";
$success = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment Status - Travel Adda</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #17B978, #0d8050);
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            background: #fff;
            padding: 40px;
            width: 450px;
            text-align: center;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            animation: fadeIn 0.6s ease-in-out;
        }
        .card h2 {
            color: #17B978;
            margin-bottom: 15px;
        }
        .card p {
            font-size: 15px;
            color: #444;
        }
        .btn {
            display: inline-block;
            margin-top: 20px;
            background: #17B978;
            color: white;
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            transition: background 0.3s ease;
        }
        .btn:hover {
            background: #139a62;
        }
        .icon {
            font-size: 60px;
            color: #17B978;
            margin-bottom: 15px;
        }
        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(-10px);}
            to {opacity: 1; transform: translateY(0);}
        }
    </style>
</head>
<body>
    <div class="card">
        <?php if ($success) { ?>
            <div class="icon">✅</div>
            <h2>Payment Successful!</h2>
            <p>Your booking ID <b>#<?php echo $booking_id; ?></b> has been paid successfully.</p>
            <a class="btn" href="customer_dashboard.php">Go to Dashboard</a>
        <?php } else { ?>
            <div class="icon" style="color:red;">❌</div>
            <h2 style="color:red;">Payment Failed</h2>
            <p><?php echo mysqli_error($conn); ?></p>
            <a class="btn" href="payment.php?booking_id=<?php echo $booking_id; ?>">Try Again</a>
        <?php } ?>
    </div>
</body>
</html>
