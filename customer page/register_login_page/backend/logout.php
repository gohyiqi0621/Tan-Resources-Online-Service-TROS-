<?php
session_start();  // Make sure the session is started
session_unset();  // Unset all session variables
session_destroy();  // Destroy the session

// Ensure the redirect path is correct
header("Location: ../../../guest%20page/guest.html");  // Encoding spaces properly (%20)
exit();  // Exit to prevent further execution
?>
