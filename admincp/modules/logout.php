<?php
require_once('../config/config.php');
include("../../utils.php");

function logoutUser()
{
    // Unset all session variables
    unset($_SESSION['user']);
    return true;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    session_start();
    logoutUser();
    redirect('../login-admin.php?action=login&status=logout_success');
} else {
    die('Invalid request method.');
}
