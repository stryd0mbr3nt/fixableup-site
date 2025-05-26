<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit;
}
?>
<h2>Welcome, Admin</h2>
<a href="logout.php">Logout</a>

