<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $query = "SELECT * FROM admins WHERE username='$username' AND password='$password'";
    $result = $conn->query($query);

    if ($result && $result->num_rows === 1) {
        $_SESSION['admin'] = $username;
        header("Location: admin_dashboard.php");
    } else {
        echo "Invalid login";
    }
}
?>
<form method="post">
  Username: <input name="username" type="text" /><br />
  Password: <input name="password" type="password" /><br />
  <input type="submit" value="Login" />
</form>

