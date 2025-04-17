<?php

/**
 * Redirect to a specific page
 * @param string $location Redirect location
 */
function redirect($location)
{
    header("Location: $location");
    exit();
}

function responseJson($data)
{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
    exit();
}
