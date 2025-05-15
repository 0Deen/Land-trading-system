<?php
session_start();
if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
<<<<<<< HEAD
?>
=======
?>
>>>>>>> 3fb3160a580a1570b5e2a65859cae3e1ede7da6a
