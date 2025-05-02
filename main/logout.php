<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Your logout logic here
session_unset();
session_destroy();

// Redirect or output message
header("Location: login.php");
exit();
