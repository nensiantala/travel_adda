<?php
$conn = new mysqli("localhost", "root", "", "travel_adda");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<?php
$servername = "localhost";  // usually localhost
$username = "root";         // your db username
$password = "";             // your db password
$dbname = "travel_adda";    // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
