<?php
$mysqli = new mysqli("localhost", "root", "khongrotmon", "web_kpop");
$key = 'matkhauahihi';
// Check connection
if ($mysqli->connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli->connect_error;
  exit();
}

/**
 * Redirect to a specific page
 * @param string $location Redirect location
 */
function redirect($location)
{
  header("Location: $location");
  exit();
}
