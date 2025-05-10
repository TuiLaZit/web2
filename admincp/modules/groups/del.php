<?php
require_once('../../config/config.php');

function executeQuery($mysqli, $query)
{
    if (mysqli_query($mysqli, $query)) {
        header('Location: ../../index.php?action=groups');
        exit();
    } else {
        die('Database error: ' . mysqli_error($mysqli));
    }
}

if (isset($_GET['IdSP'])) {
    $IdGRP = $_GET['IdGRP'];

    $query = "DELETE FROM nhom WHERE IdGRP = ?";

    $stmt = mysqli_prepare($mysqli, $query);
    if (!$stmt) {
        die('SQL prepare error: ' . mysqli_error($mysqli));
    }

    mysqli_stmt_bind_param($stmt, 'i', $IdSP);

    if (mysqli_stmt_execute($stmt)) {
        header('Location: ../../index.php?action=products');
        exit();
    } else {
        die('Database error: ' . mysqli_stmt_error($stmt));
    }

    mysqli_stmt_close($stmt);
    mysqli_close($mysqli);
} else {
    die('Invalid request id.');
}
