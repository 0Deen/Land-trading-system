<?php
include 'connect.php';
session_start();  // make sure the session is started

// Destroy all session data
$_SESSION = [];
session_unset();
session_destroy();

// Delete the user_id cookie
setcookie('user_id', '', time() - 3600, '/');

// Redirect to homepage
header('location:../home.php');
exit;
?>
