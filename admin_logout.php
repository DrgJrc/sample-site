<?php
session_start();

// Destroy the admin session
session_destroy();

// Redirect to the login page
header('Location: index.php');
exit;
?>