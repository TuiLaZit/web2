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
