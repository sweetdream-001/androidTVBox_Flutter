<?php
session_start();

// Check if user is logged in
if (isset($_SESSION['valid']) && $_SESSION['valid'] !== '') {
    // User is logged in, show dashboard
    include 'dashboard.php';
} else {
    // User not logged in, show login page
    include 'login.php';
}
