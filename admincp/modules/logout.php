<?php
require_once('../config/config.php');

/**
 * Log out user
 */
function logoutUser()
{
    // Unset all session variables
    unset($_SESSION['user']);
    return true;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    session_start();
    logoutUser();
    redirect('../login.php?action=login&status=logout_success');
} else {
    die('Invalid request method.');
}
