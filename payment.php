<?php
session_start();
include "db_connect.php";

if (!isset($_GET['booking_id'])) {
    die("Invalid Request");
}
$booking_id = $_GET['booking_id'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment - Travel Adda</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #17B978, #0d8050);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .box {
            background: #fff;
            padding: 40px;
            width: 400px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            text-align: center;
            animation: fadeIn 0.6s ease-in-out;
        }
        .box h2 {
            margin-bottom: 20px;
            color: #17B978;
        }
        .box p {
            margin: 10px 0;
            color: #444;
            font-size: 15px;
        }
        .box input {
            width: 90%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            outline: none;
            font-size: 14px;
            transition: border 0.3s;
        }
        .box input:focus {
            border-color: #17B978;
            box-shadow: 0 0 5px rgba(23,185,120,0.4);
        }
        .box button {
            width: 95%;
            padding: 12px;
            background: #17B978;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 15px;
            transition: background 0.3s ease;
        }
        .box button:hover {
            background: #139a62;
        }
        @keyframes fadeIn {
            from {opacity: 0; transform: scale(0.95);}
            to {opacity: 1; transform: scale(1);}
        }
    </style>
</head>
<body>
    <div class="box">
        <h2>Payment Gateway</h2>
        <p><b>Booking ID:</b> <?php echo $booking_id; ?></p>
        <p>Enter Card Details</p>
        <form action="payment_success.php" method="POST">
            <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">
            <input type="text" name="card_number" placeholder="Card Number" required><br>
            <input type="text" name="expiry" placeholder="Expiry Date (MM/YY)" required><br>
            <input type="password" name="cvv" placeholder="CVV" required><br>
            <button type="submit">Pay Now</button>
        </form>
    </div>
</body>
</html>
