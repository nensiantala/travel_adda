<?php
session_start();
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Redirect to agent login page
header("Location: agent_login.php");
exit();
?>
