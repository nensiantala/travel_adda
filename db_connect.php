<?php
$conn = new mysqli("localhost", "root", "", "travel_adda");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<?php
$servername = "bpetbnfz8x1tmtklzzk0-mysql.services.clever-cloud.com";  // usually localhost
$username = "uelp3faahgfbpa2c";         // your db username
$password = "GolwS56KZiowaUMIdmob";             // your db password
$dbname = "bpetbnfz8x1tmtklzzk0";    // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
